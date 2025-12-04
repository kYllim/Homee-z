<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Event;
use App\Repository\UserHouseholdRepository;

class EventProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor,
        private Security $security,
        private UserHouseholdRepository $userHouseholdRepository
    ) {}

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        // Si ce n’est pas un Event, on délègue
        if (!$data instanceof Event) {
            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        $user = $this->security->getUser();
        if (!$user) {
            throw new \Exception("Utilisateur non connecté.");
        }

        // Attribution automatique du creator si absent
        if (!$data->getCreator()) {
            $data->setCreator($user);
        }

        // Attribution automatique du household si absent
        if (!$data->getHousehold()) {
            $userHouseholds = $user->getUserHouseholds();
            if (!$userHouseholds->isEmpty()) {
                // On prend le premier foyer de l'utilisateur
                $data->setHousehold($userHouseholds->first()->getHousehold());
            } else {
                // L'utilisateur n'a aucun foyer → on bloque la création
                throw new \Exception("Impossible de créer un événement : l'utilisateur n'a pas de foyer.");
            }
        }

        // Création automatique de la date de création si nécessaire
        if (!$data->getCreatedAt()) {
            $data->setCreatedAt(new \DateTimeImmutable());
        }

        // ⚡ Automatisation du status
        $now = new \DateTimeImmutable();
        if (!$data->getStatus() && $data->getStartAt() && $data->getEndAt()) {
            if ($data->getStartAt() > $now) {
                $data->setStatus('à faire');
            } elseif ($data->getEndAt() < $now) {
                $data->setStatus('terminé');
            } else {
                $data->setStatus('en cours');
            }
        }

        // Vérification des droits pour PATCH / DELETE
        if (in_array($operation->getName(), ['patch', 'delete', 'put'])) {
            if ($data->getCreator() !== $user) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException(
                    "Vous n'avez pas le droit de modifier ou supprimer cet événement."
                );
            }
        }

        // Tout est OK → on persiste
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
