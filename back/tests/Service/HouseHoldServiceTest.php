<?php

namespace App\Tests\Service;

use App\Service\HouseHoldService;
use App\Entity\Household;
use App\Entity\Person;
use App\Entity\PersonHousehold;
use App\Entity\User;
use App\Enum\HouseHoldEnum;
use App\Enum\PersonEnum;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class HouseHoldServiceTest extends TestCase
{
    private HouseHoldService $service;
    private EntityManagerInterface|\PHPUnit\Framework\MockObject\MockObject $entityManager;
    private EntityRepository|\PHPUnit\Framework\MockObject\MockObject $householdRepository;
    private EntityRepository|\PHPUnit\Framework\MockObject\MockObject $personHouseholdRepository;
    private EntityRepository|\PHPUnit\Framework\MockObject\MockObject $userRepository;

    protected function setUp(): void
    {
        // Mock de l'EntityManager
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        // Mock des repositories
        $this->householdRepository = $this->createMock(EntityRepository::class);
        $this->personHouseholdRepository = $this->createMock(EntityRepository::class);
        $this->userRepository = $this->createMock(EntityRepository::class);

        // Configuration de getRepository pour retourner les bons mocks
        $this->entityManager
            ->method('getRepository')
            ->willReturnCallback(function ($entityClass) {
                if ($entityClass === Household::class) {
                    return $this->householdRepository;
                }
                if ($entityClass === PersonHousehold::class) {
                    return $this->personHouseholdRepository;
                }
                if ($entityClass === User::class) {
                    return $this->userRepository;
                }
                return null;
            });

        $this->service = new HouseHoldService($this->entityManager);
    }

    /**
     * Test de génération d'un code d'accès
     */
    public function testCreateHouseHoldCodeReturnsString(): void
    {
        $code = $this->service->createHouseHoldCode();

        $this->assertIsString($code);
        $this->assertEquals(8, strlen($code), 'Le code doit faire 8 caractères');
    }

    /**
     * Test que le code généré contient uniquement des caractères valides
     */
    public function testCreateHouseHoldCodeContainsValidCharacters(): void
    {
        $code = $this->service->createHouseHoldCode();

        // Le code doit contenir uniquement des caractères alphanumériques et symboles définis
        $this->assertMatchesRegularExpression('/^[A-Z0-9@#$%^&*]{8}$/', $code);
    }

    /**
     * Test que chaque appel génère un code différent (très probablement)
     */
    public function testCreateHouseHoldCodeGeneratesUniqueCode(): void
    {
        $codes = [];
        for ($i = 0; $i < 10; $i++) {
            $codes[] = $this->service->createHouseHoldCode();
        }

        $uniqueCodes = array_unique($codes);

        // Il devrait y avoir au moins 8 codes uniques sur 10 (probabilité très élevée)
        $this->assertGreaterThanOrEqual(8, count($uniqueCodes));
    }

    /**
     * Test HouseHoldExist avec un nom existant
     */
    public function testHouseHoldExistByNameReturnsHousehold(): void
    {
        $household = new Household();
        $household->setName('Test Household');

        $this->householdRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'Test Household'])
            ->willReturn($household);

        $result = $this->service->HouseHoldExist(name: 'Test Household');

        $this->assertSame($household, $result);
    }

    /**
     * Test HouseHoldExist avec un nom inexistant
     */
    public function testHouseHoldExistByNameReturnsFalseWhenNotFound(): void
    {
        $this->householdRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'Nonexistent Household'])
            ->willReturn(null);

        $result = $this->service->HouseHoldExist(name: 'Nonexistent Household');

        $this->assertFalse($result);
    }

    /**
     * Test HouseHoldExist avec un code d'accès existant
     */
    public function testHouseHoldExistByAccessCodeReturnsHousehold(): void
    {
        $household = new Household();
        $household->setAccessCode('ABC12345');

        $this->householdRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['accessCode' => 'ABC12345'])
            ->willReturn($household);

        $result = $this->service->HouseHoldExist(accessCode: 'ABC12345');

        $this->assertSame($household, $result);
    }

    /**
     * Test HouseHoldExist avec un code d'accès inexistant
     */
    public function testHouseHoldExistByAccessCodeReturnsFalseWhenNotFound(): void
    {
        $this->householdRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['accessCode' => 'INVALID1'])
            ->willReturn(null);

        $result = $this->service->HouseHoldExist(accessCode: 'INVALID1');

        $this->assertFalse($result);
    }

    /**
     * Test HouseHoldExist sans paramètres
     */
    public function testHouseHoldExistWithoutParametersReturnsFalse(): void
    {
        $result = $this->service->HouseHoldExist();

        $this->assertFalse($result);
    }

    /**
     * Test checkHouseHoldAdmin avec un administrateur valide
     */
    public function testCheckHouseHoldAdminReturnsTrueForAdmin(): void
    {
        $household = $this->createMock(Household::class);
        $person = $this->createMock(Person::class);
        $personHousehold = $this->createMock(PersonHousehold::class);

        $person->method('getId')->willReturn(1);
        $personHousehold->method('getPerson')->willReturn($person);
        $personHousehold->method('getRole')->willReturn(HouseHoldEnum::ADMIN);

        // Créer une vraie ArrayCollection au lieu d'un array
        $collection = new \Doctrine\Common\Collections\ArrayCollection([$personHousehold]);
        $household->method('getMemberships')->willReturn($collection);

        $this->householdRepository
            ->method('findOneBy')
            ->with(['accessCode' => 'ABC12345'])
            ->willReturn($household);

        $result = $this->service->checkHouseHoldAdmin(1, 'ABC12345');

        $this->assertTrue($result);
    }

    /**
     * Test checkHouseHoldAdmin avec un membre non-admin
     */
    public function testCheckHouseHoldAdminReturnsFalseForNonAdmin(): void
    {
        $household = $this->createMock(Household::class);
        $person = $this->createMock(Person::class);
        $personHousehold = $this->createMock(PersonHousehold::class);

        $person->method('getId')->willReturn(1);
        $personHousehold->method('getPerson')->willReturn($person);
        $personHousehold->method('getRole')->willReturn(HouseHoldEnum::MEMBER);

        // Créer une vraie ArrayCollection au lieu d'un array
        $collection = new \Doctrine\Common\Collections\ArrayCollection([$personHousehold]);
        $household->method('getMemberships')->willReturn($collection);

        $this->householdRepository
            ->method('findOneBy')
            ->with(['accessCode' => 'ABC12345'])
            ->willReturn($household);

        $result = $this->service->checkHouseHoldAdmin(1, 'ABC12345');

        $this->assertFalse($result);
    }

    /**
     * Test checkPersonInHouseHold avec une personne qui appartient au foyer
     */
    public function testCheckPersonInHouseHoldReturnsTrueWhenPersonIsInHousehold(): void
    {
        $personHousehold = new PersonHousehold();

        $this->personHouseholdRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['person' => 1, 'household' => 1])
            ->willReturn($personHousehold);

        $result = $this->service->checkPersonInHouseHold(1, 1);

        $this->assertTrue($result);
    }

    /**
     * Test checkPersonInHouseHold avec une personne qui n'appartient pas au foyer
     */
    public function testCheckPersonInHouseHoldReturnsFalseWhenPersonNotInHousehold(): void
    {
        $this->personHouseholdRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['person' => 1, 'household' => 1])
            ->willReturn(null);

        $result = $this->service->checkPersonInHouseHold(1, 1);

        $this->assertFalse($result);
    }

    /**
     * Test addPersonToHousehold sans aucune donnée
     */
    public function testAddPersonToHouseholdReturnsFalseWithNoData(): void
    {
        $household = new Household();

        $result = $this->service->addPersonToHousehold($household, 'MEMBER');

        $this->assertFalse($result);
    }

    /**
     * Test addPersonToHousehold pour ajouter un enfant (sans email)
     */
    public function testAddPersonToHouseholdAddsChild(): void
    {
        $household = new Household();
        $household->setName('Test Household');

        $this->entityManager
            ->expects($this->exactly(2))
            ->method('persist');

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $result = $this->service->addPersonToHousehold(
            $household,
            'member', // Utiliser la vraie valeur de l'enum (minuscule)
            'Jean',
            'Dupont'
        );

        $this->assertInstanceOf(PersonHousehold::class, $result);
    }

    /**
     * Test addPersonToHousehold avec un email d'utilisateur inexistant
     */
    public function testAddPersonToHouseholdReturnsFalseForNonexistentUser(): void
    {
        $household = new Household();

        $this->userRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'nonexistent@example.com'])
            ->willReturn(null);

        $result = $this->service->addPersonToHousehold(
            $household,
            'member',
            'John',
            'Doe',
            'nonexistent@example.com'
        );

        $this->assertFalse($result);
    }

    /**
     * Test addPersonToHousehold avec un utilisateur déjà membre du foyer
     */
    public function testAddPersonToHouseholdReturnsFalseForExistingMember(): void
    {
        $household = new Household();
        $household->setName('Test Household');

        // Mock réflexion pour définir l'ID
        $reflection = new \ReflectionClass($household);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setValue($household, 1);

        $person = new Person();
        $reflection = new \ReflectionClass($person);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setValue($person, 1);

        $user = $this->createMock(User::class);
        $user->method('getPerson')->willReturn($person);

        $this->userRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'existing@example.com'])
            ->willReturn($user);

        // Simuler que la personne est déjà dans le foyer
        $this->personHouseholdRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['person' => 1, 'household' => 1])
            ->willReturn(new PersonHousehold());

        $result = $this->service->addPersonToHousehold(
            $household,
            'member',
            'John',
            'Doe',
            'existing@example.com'
        );

        $this->assertFalse($result);
    }

    /**
     * Test addPersonToHousehold avec un utilisateur valide
     */
    public function testAddPersonToHouseholdAddsExistingUser(): void
    {
        $household = new Household();
        $household->setName('Test Household');

        $reflection = new \ReflectionClass($household);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setValue($household, 1);

        $person = new Person();
        $reflection = new \ReflectionClass($person);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setValue($person, 2);

        $user = $this->createMock(User::class);
        $user->method('getPerson')->willReturn($person);

        $this->userRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'newuser@example.com'])
            ->willReturn($user);

        // La personne n'est pas encore dans le foyer
        $this->personHouseholdRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['person' => 2, 'household' => 1])
            ->willReturn(null);

        $this->entityManager
            ->expects($this->once())
            ->method('persist');

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $result = $this->service->addPersonToHousehold(
            $household,
            'member',
            'New',
            'User',
            'newuser@example.com'
        );

        $this->assertInstanceOf(PersonHousehold::class, $result);
    }
}
