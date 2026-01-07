<?php

namespace App\Tests\EventListener;

use App\Entity\User;
use App\Entity\Person;
use App\EventListener\JWTCreatedListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use PHPUnit\Framework\TestCase;

class JWTCreatedListenerTest extends TestCase
{
    private JWTCreatedListener $listener;

    protected function setUp(): void
    {
        $this->listener = new JWTCreatedListener();
    }

    /**
     * Test que le listener ajoute firstName au payload JWT
     */
    public function testAddsFirstNameToPayload(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getFirstName')->willReturn('John');
        $user->method('getLastName')->willReturn('Doe');
        $user->method('getId')->willReturn(123);

        $payload = ['username' => 'test@example.com'];
        $event = $this->createMock(JWTCreatedEvent::class);
        $event->method('getUser')->willReturn($user);
        $event->method('getData')->willReturn($payload);

        $event->expects($this->once())
            ->method('setData')
            ->with($this->callback(function ($data) {
                return isset($data['firstName']) && $data['firstName'] === 'John';
            }));

        $this->listener->onJWTCreated($event);
    }

    /**
     * Test que le listener ajoute tous les champs nécessaires au payload JWT
     */
    public function testAddsAllFieldsToPayload(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getFirstName')->willReturn('John');
        $user->method('getLastName')->willReturn('Doe');
        $user->method('getId')->willReturn(123);

        $payload = ['username' => 'test@example.com'];
        $event = $this->createMock(JWTCreatedEvent::class);
        $event->method('getUser')->willReturn($user);
        $event->method('getData')->willReturn($payload);

        $event->expects($this->once())
            ->method('setData')
            ->with($this->callback(function ($data) {
                return isset($data['firstName']) && $data['firstName'] === 'John'
                    && isset($data['id']) && $data['id'] === 123;
            }));

        $this->listener->onJWTCreated($event);
    }

    /**
     * Test que le listener ajoute l'ID utilisateur au payload JWT
     */
    public function testAddsUserIdToPayload(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getFirstName')->willReturn('John');
        $user->method('getLastName')->willReturn('Doe');
        $user->method('getId')->willReturn(123);

        $payload = ['username' => 'test@example.com'];
        $event = $this->createMock(JWTCreatedEvent::class);
        $event->method('getUser')->willReturn($user);
        $event->method('getData')->willReturn($payload);

        $event->expects($this->once())
            ->method('setData')
            ->with($this->callback(function ($data) {
                return isset($data['id']) && $data['id'] === 123;
            }));

        $this->listener->onJWTCreated($event);
    }

    /**
     * Test que le listener préserve les données existantes du payload
     */
    public function testPreservesExistingPayloadData(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getFirstName')->willReturn('John');
        $user->method('getLastName')->willReturn('Doe');
        $user->method('getId')->willReturn(123);

        $payload = [
            'username' => 'test@example.com',
            'exp' => 1234567890,
            'iat' => 1234567800
        ];

        $event = $this->createMock(JWTCreatedEvent::class);
        $event->method('getUser')->willReturn($user);
        $event->method('getData')->willReturn($payload);

        $event->expects($this->once())
            ->method('setData')
            ->with($this->callback(function ($data) use ($payload) {
                return $data['username'] === $payload['username']
                    && $data['exp'] === $payload['exp']
                    && $data['iat'] === $payload['iat']
                    && isset($data['firstName'])
                    && isset($data['id']);
            }));

        $this->listener->onJWTCreated($event);
    }

    /**
     * Test que le listener gère correctement un utilisateur sans méthodes getFirstName/getLastName/getId
     */
    public function testHandlesUserWithoutRequiredMethods(): void
    {
        $user = $this->createMock(\Symfony\Component\Security\Core\User\UserInterface::class);

        $payload = ['username' => 'test@example.com'];
        $event = $this->createMock(JWTCreatedEvent::class);
        $event->method('getUser')->willReturn($user);
        $event->method('getData')->willReturn($payload);

        // Le listener ne devrait pas planter même si les méthodes n'existent pas
        $event->expects($this->once())
            ->method('setData')
            ->with($payload); // Payload inchangé

        $this->listener->onJWTCreated($event);
    }
}
