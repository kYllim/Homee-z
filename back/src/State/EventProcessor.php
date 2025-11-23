<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Event;
use App\Repository\UserRepository;
use App\Repository\HouseholdRepository;

class EventProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor,
        private UserRepository $userRepository,
        private HouseholdRepository $householdRepository
    ) {}

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$data instanceof Event) {
            // Si ce n'est pas un Event, on laisse passer
            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        // ----- Simuler creator et household pour le dev -----
        if (!$data->getCreator()) {
            $user = $this->userRepository->find(9); // ID fictif
            if ($user) {
                $data->setCreator($user);
            }
        }

        if (!$data->getHousehold()) {
            $household = $this->householdRepository->find(3); // ID fictif
            if ($household) {
                $data->setHousehold($household);
            }
        }

        // Si createdAt n'est pas défini
        if (!$data->getCreatedAt()) {
            $data->setCreatedAt(new \DateTimeImmutable());
        }

        // ----- Optionnel : traiter différemment selon l’opération -----
        switch ($operation->getName()) {
            case 'post':
                // Déjà géré par la simulation ci-dessus
                break;

            case 'patch':
            case 'put':
                // On peut éventuellement mettre à jour updatedAt si tu veux
                break;

            case 'delete':
                // Pas besoin de changer creator/household
                break;
        }

        // Persister l'entité
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
