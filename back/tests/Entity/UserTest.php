<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Person;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * Test de création d'un utilisateur avec des données valides
     */
    public function testCreateUserWithValidData(): void
    {
        $user = new User();
        $person = new Person();

        $user->setEmail('test@example.com')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setPassword('hashedpassword123')
            ->setPerson($person);

        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('John', $user->getFirstName());
        $this->assertEquals('Doe', $user->getLastName());
        $this->assertEquals('hashedpassword123', $user->getPassword());
        $this->assertEquals($person, $user->getPerson());
    }

    /**
     * Test de l'état de vérification par défaut
     */
    public function testUserIsNotVerifiedByDefault(): void
    {
        $user = new User();

        $this->assertFalse($user->isVerified());
    }

    /**
     * Test de la modification de l'état de vérification
     */
    public function testSetIsVerified(): void
    {
        $user = new User();

        $user->setIsVerified(true);

        $this->assertTrue($user->isVerified());
    }

    /**
     * Test du token de vérification
     */
    public function testVerificationToken(): void
    {
        $user = new User();
        $token = 'test-verification-token-123';

        $user->setVerificationToken($token);

        $this->assertEquals($token, $user->getVerificationToken());
    }

    /**
     * Test de la réinitialisation du token de vérification
     */
    public function testResetVerificationToken(): void
    {
        $user = new User();
        $user->setVerificationToken('initial-token');

        $user->setVerificationToken(null);

        $this->assertNull($user->getVerificationToken());
    }

    /**
     * Test des rôles par défaut de l'utilisateur
     */
    public function testUserHasDefaultRole(): void
    {
        $user = new User();

        $roles = $user->getRoles();

        $this->assertContains('ROLE_USER', $roles);
    }

    /**
     * Test de l'ajout de rôles supplémentaires
     */
    public function testSetRoles(): void
    {
        $user = new User();

        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $roles = $user->getRoles();

        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertContains('ROLE_USER', $roles);
    }

    /**
     * Test de getUserIdentifier (requis par Symfony UserInterface)
     */
    public function testGetUserIdentifier(): void
    {
        $user = new User();
        $email = 'identifier@example.com';

        $user->setEmail($email);

        $this->assertEquals($email, $user->getUserIdentifier());
    }

    /**
     * Test de la relation avec Person
     */
    public function testPersonRelation(): void
    {
        $user = new User();
        $person = new Person();
        $person->setFirstName('Jane')
               ->setLastName('Smith');

        $user->setPerson($person);

        $this->assertSame($person, $user->getPerson());
        $this->assertEquals('Jane', $user->getPerson()->getFirstName());
        $this->assertEquals('Smith', $user->getPerson()->getLastName());
    }

    /**
     * Test du fluent interface (chaînage des méthodes)
     */
    public function testFluentInterface(): void
    {
        $user = new User();
        $person = new Person();

        $result = $user
            ->setEmail('fluent@example.com')
            ->setFirstName('Fluent')
            ->setLastName('Test')
            ->setPassword('password')
            ->setIsVerified(true)
            ->setPerson($person);

        $this->assertSame($user, $result);
        $this->assertEquals('fluent@example.com', $user->getEmail());
        $this->assertTrue($user->isVerified());
    }
}
