<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Event;
use App\Enum\EventStatusEnum;
use App\Repository\UserHouseholdRepository;

class EventProcessor implements ProcessorInterface
{
    public function __construct(
        #[\Symfony\Component\DependencyInjection\Attribute\Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        #[\Symfony\Component\DependencyInjection\Attribute\Autowire(service: 'api_platform.doctrine.orm.state.remove_processor')]
        private ProcessorInterface $removeProcessor,
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
            $data->setCreator($user->getPerson());
        }

        // Attribution automatique du household si absent
        if (!$data->getHousehold()) {
            $person = $user->getPerson();
            if ($person) {
                $memberships = $person->getMemberships();
                if (!$memberships->isEmpty()) {
                    // On prend le premier foyer de la personne
                    $data->setHousehold($memberships->first()->getHousehold());
                } else {
                    // La personne n'a aucun foyer → on bloque la création
                    throw new \Exception("Impossible de créer un événement : vous n'appartenez à aucun foyer.");
                }
            } else {
                throw new \Exception("Impossible de créer un événement : aucune personne associée à cet utilisateur.");
            }
        }

        // Création automatique de la date de création si nécessaire
        if (!$data->getCreatedAt()) {
            $data->setCreatedAt(new \DateTimeImmutable());
        }

        // ⚡ Automatisation du status uniquement pour les nouveaux événements
        $isNewEvent = $operation->getName() === 'post';
        if ($isNewEvent && $data->getStartAt()) {
            $now = new \DateTimeImmutable();
            // Définir le statut initial basé sur les dates
            if ($data->getStartAt() > $now) {
                $data->setStatus(EventStatusEnum::PREVU->value);
            } else {
                $data->setStatus(EventStatusEnum::EN_COURS->value);
            }
        }

        // Vérification des droits pour PATCH / DELETE
        if (in_array($operation->getName(), ['patch', 'delete', 'put'])) {
            $creator = $data->getCreator();
            $currentPerson = $user->getPerson();

            // Logs de débogage
            error_log("Operation: " . $operation->getName());
            error_log("Creator ID: " . ($creator ? $creator->getId() : 'null'));
            error_log("Current Person ID: " . ($currentPerson ? $currentPerson->getId() : 'null'));

            if (!$creator || !$currentPerson || $creator->getId() !== $currentPerson->getId()) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException(
                    "Vous n'avez pas le droit de modifier ou supprimer cet événement."
                );
            }
        }

        // Tout est OK → on persiste ou supprime selon l'opération
        if ($operation instanceof DeleteOperationInterface) {
            error_log("DELETE OPERATION - Calling removeProcessor");
            return $this->removeProcessor->process($data, $operation, $uriVariables, $context);
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
