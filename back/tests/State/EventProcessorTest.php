<?php

namespace App\Tests\State;

use App\Entity\Event;
use App\Entity\User;
use App\Entity\Person;
use App\Entity\Household;
use App\Entity\PersonHousehold;
use App\State\EventProcessor;
use App\Repository\UserHouseholdRepository;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EventProcessorTest extends TestCase
{
    private EventProcessor $processor;
    private ProcessorInterface $persistProcessor;
    private ProcessorInterface $removeProcessor;
    private Security $security;
    private UserHouseholdRepository $householdRepository;

    protected function setUp(): void
    {
        $this->persistProcessor = $this->createMock(ProcessorInterface::class);
        $this->removeProcessor = $this->createMock(ProcessorInterface::class);
        $this->security = $this->createMock(Security::class);
        $this->householdRepository = $this->createMock(UserHouseholdRepository::class);

        $this->processor = new EventProcessor(
            $this->persistProcessor,
            $this->removeProcessor,
            $this->security,
            $this->householdRepository
        );
    }

    public function testAjouteCreateurAuto(): void
    {
        $user = $this->createUser();
        $household = $this->createHousehold();
        $this->setupUserWithHousehold($user, $household);

        $event = new Event();
        $event->setTitle('Test Event');
        $event->setStartAt(new \DateTimeImmutable('+1 day'));
        $event->setEndAt(new \DateTimeImmutable('+2 days'));

        $this->security->method('getUser')->willReturn($user);
        $this->persistProcessor->method('process')->willReturn($event);

        $result = $this->processor->process($event, new Post(), [], []);

        $this->assertSame($user->getPerson(), $event->getCreator());
    }

    public function testStatutPrevuPourFutur(): void
    {
        $user = $this->createUser();
        $household = $this->createHousehold();
        $this->setupUserWithHousehold($user, $household);

        $event = new Event();
        $event->setTitle('Future Event');
        $event->setStartAt(new \DateTimeImmutable('+1 day'));
        $event->setEndAt(new \DateTimeImmutable('+2 days'));

        $this->security->method('getUser')->willReturn($user);
        $this->persistProcessor->method('process')->willReturn($event);

        // Créer une vraie opération Post avec le bon nom
        $operation = new Post(name: 'post');
        $this->processor->process($event, $operation, [], []);

        $this->assertEquals('prévu', $event->getStatus());
    }

    public function testStatutEnCoursPourMaintenant(): void
    {
        $user = $this->createUser();
        $household = $this->createHousehold();
        $this->setupUserWithHousehold($user, $household);

        $event = new Event();
        $event->setTitle('Current Event');
        $event->setStartAt(new \DateTimeImmutable('-1 hour'));
        $event->setEndAt(new \DateTimeImmutable('+1 hour'));

        $this->security->method('getUser')->willReturn($user);
        $this->persistProcessor->method('process')->willReturn($event);

        $operation = new Post(name: 'post');
        $this->processor->process($event, $operation, [], []);

        $this->assertEquals('en cours', $event->getStatus());
    }

    public function testAjouteFoyerAuto(): void
    {
        $user = $this->createUser();
        $household = $this->createHousehold();
        $this->setupUserWithHousehold($user, $household);

        $event = new Event();
        $event->setTitle('Test Event');
        $event->setStartAt(new \DateTimeImmutable('+1 day'));

        $this->security->method('getUser')->willReturn($user);
        $this->persistProcessor->method('process')->willReturn($event);

        $result = $this->processor->process($event, new Post(), [], []);

        $this->assertSame($household, $event->getHousehold());
    }

    public function testRefuseEditionAutreUtilisateur(): void
    {
        $creator = $this->createUser(1);
        $otherUser = $this->createUser(2);
        $household = $this->createHousehold();
        
        // Assurer que otherUser a bien une Person
        $this->assertNotNull($otherUser->getPerson());
        $this->assertNotNull($creator->getPerson());

        $event = new Event();
        $event->setTitle('Test Event');
        $event->setStartAt(new \DateTimeImmutable('+1 day'));
        $event->setCreator($creator->getPerson());
        $event->setHousehold($household);
        $event->setCreatedAt(new \DateTimeImmutable());

        $this->security->method('getUser')->willReturn($otherUser);

        $this->expectException(AccessDeniedException::class);

        $operation = new Patch(name: 'patch');
        $this->processor->process($event, $operation, [], []);
    }

    public function testAutoriseEditionCreateur(): void
    {
        $user = $this->createUser();
        $household = $this->createHousehold();

        $event = new Event();
        $event->setTitle('Test Event');
        $event->setStartAt(new \DateTimeImmutable('+1 day'));
        $event->setCreator($user->getPerson());
        $event->setHousehold($household);

        $this->security->method('getUser')->willReturn($user);
        $this->persistProcessor->method('process')->willReturn($event);

        $result = $this->processor->process($event, new Patch(), [], []);

        $this->assertInstanceOf(Event::class, $result);
    }

    public function testAutoriseSuppressionCreateur(): void
    {
        $user = $this->createUser();
        $household = $this->createHousehold();

        $event = new Event();
        $event->setTitle('Test Event');
        $event->setStartAt(new \DateTimeImmutable('+1 day'));
        $event->setCreator($user->getPerson());
        $event->setHousehold($household);

        $this->security->method('getUser')->willReturn($user);
        $this->removeProcessor->method('process')->willReturn(null);

        $result = $this->processor->process($event, new Delete(), [], []);

        $this->assertNull($result);
    }

    public function testAjouteCreatedAtAuto(): void
    {
        $user = $this->createUser();
        $household = $this->createHousehold();
        $this->setupUserWithHousehold($user, $household);

        $event = new Event();
        $event->setTitle('Test Event');
        $event->setStartAt(new \DateTimeImmutable('+1 day'));

        $this->security->method('getUser')->willReturn($user);
        $this->persistProcessor->method('process')->willReturn($event);

        $result = $this->processor->process($event, new Post(), [], []);

        $this->assertInstanceOf(\DateTimeImmutable::class, $event->getCreatedAt());
    }

    private function createUser(int $id = 1): User
    {
        $user = new User();
        $person = new Person();
        
        // Utiliser reflection pour définir l'ID
        $reflection = new \ReflectionClass($person);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($person, $id);
        
        $user->setPerson($person);
        
        return $user;
    }

    private function createHousehold(): Household
    {
        $household = new Household();
        $household->setName('Test Household');
        $household->setAccessCode('TEST123');
        
        return $household;
    }

    private function setupUserWithHousehold(User $user, Household $household): void
    {
        $personHousehold = new PersonHousehold();
        $personHousehold->setPerson($user->getPerson());
        $personHousehold->setHousehold($household);
        
        $user->getPerson()->addMembership($personHousehold);
    }
}
