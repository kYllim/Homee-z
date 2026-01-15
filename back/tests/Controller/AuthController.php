<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

class AuthController extends WebTestCase
{

  public function testRegisterSuccess(): void
  {
    $client = static::createClient();

    $payload = [
      'email' => 'test@example.com',
      'name' => 'Doe',
      'firstname' => 'John',
      'password' => 'Password123!'
    ];

    $client->request(
      'POST',
      '/api/register',
      [],
      [],
      ['CONTENT_TYPE' => 'application/json'],
      json_encode($payload)
    );

    $this->assertResponseStatusCodeSame(201);

    $responseData = json_decode($client->getResponse()->getContent(), true);

    $this->assertArrayHasKey('token', $responseData);
    $this->assertEquals('succes', $responseData['status']);


    $entityManager = self::getContainer()->get('doctrine')->getManager();
    $user = $entityManager->getRepository(User::class)->findOneBy([
      'email' => 'test@example.com'
    ]);

    $this->assertNotNull($user);
    $this->assertFalse($user->isVerified());
  }

  public function testRegisterEmailAlreadyUsed(): void
  {
    $client = static::createClient();

    $payload = [
      'email' => 'test@example.com',
      'name' => 'Doe',
      'firstname' => 'John',
      'password' => 'Password123!'
    ];

    $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($payload));
    $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($payload));

    $this->assertResponseStatusCodeSame(409);
  }

  public function testVerifyEmail(): void
  {
    $client = static::createClient();
    $em = self::getContainer()->get('doctrine')->getManager();

    $user = $em->getRepository(User::class)->findOneBy(['email' => 'test@example.com']);

    $client->request('GET', '/api/verifyEmail?token=' . $user->getVerificationToken());

    $this->assertResponseStatusCodeSame(201);

    $em->refresh($user);
    $this->assertTrue($user->isVerified());
  }
}
