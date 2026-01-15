<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

class AuthControllerTest extends WebTestCase
{

  private function authenticate($client, string $email): void
  {
    $em = self::getContainer()->get('doctrine')->getManager();
    $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

    $jwtManager = self::getContainer()->get('lexik_jwt_authentication.jwt_manager');
    $token = $jwtManager->create($user);

    $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $token));
  }

  public function testCreateHouseHold(): void
  {
    $client = static::createClient();
    $this->authenticate($client, 'test@example.com');

    $client->request(
      'POST',
      '/api/CreateHouseHold',
      [],
      [],
      ['CONTENT_TYPE' => 'application/json'],
      json_encode(['name' => 'Ma Famille'])
    );

    $this->assertResponseStatusCodeSame(200);

    $response = json_decode($client->getResponse()->getContent(), true);
    $this->assertArrayHasKey('accessCode', $response);
  }
}
