<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTCreatedListener
{
    /**
     * @param JWTCreatedEvent $event
     */
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $payload = $event->getData();

        // Ajouter le prénom, le nom et l'ID au payload du token
        if (method_exists($user, 'getFirstName')) {
            $payload['firstName'] = $user->getFirstName();
        }

        if (method_exists($user, 'getId')) {
            $payload['id'] = $user->getId();
        }

        $event->setData($payload);
    }
}
