<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\HouseHoldService;
use App\Entity\Household;
use App\Entity\PersonHousehold;
use App\Enum\HouseHoldEnum;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


final class HouseHoldController extends AbstractController
{
    private HouseHoldService $houseHoldService;
    private EntityManagerInterface $em;

    public function __construct(HouseHoldService $houseHoldService, EntityManagerInterface $em) {
        $this->houseHoldService = $houseHoldService;
        $this->em = $em;
    }

    #[Route('/api/CreateHouseHold', name: 'api_CreateHouseHold', methods: 'POST')]
    public function CreateHouseHold(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        $houseHoldName = $data['name'];
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $person = $user->getPerson();

        // On vérifie que le nom du foyer n'est pas pris
        $existingHouseHold = $this->houseHoldService->HouseHoldExist(name : $houseHoldName);
        if($existingHouseHold) {
            return new JsonResponse(['message' => 'Un foyer avec ce nom existe déja !'], 404);
        }

        // On initialise les entités
        $PersonHousehold = new PersonHousehold();
        $houseHold = new Household();

        // On insére les données
        $AccesCode = $this->houseHoldService->createHouseHoldCode();
        $houseHold->setName($houseHoldName)->setAccessCode($AccesCode);

        // On va insérer l'utilisateur en tant que chef du foyer
        $PersonHousehold->setRole(HouseHoldEnum::ADMIN)->setHouseHold($houseHold)->setPerson($person);

        // On persiste les données
        $this->em->persist($houseHold);
        $this->em->persist($PersonHousehold);
        $this->em->flush();

        return new JsonResponse ([
            'accessCode' => $AccesCode
        ],200);
    }

    #[Route('/api/JoinHouseHold', name: 'api_JoinHouseHold', methods: 'POST')]
    public function JoinHouseHold(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        // On récupére l'utilisateur et le code d'accès
        $accessCode = $data['accessCode'];
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $person = $user->getPerson();

        // On vérifie que le foyer existe
        $houseHold = $this->houseHoldService->HouseHoldExist($accessCode);
        if(!$houseHold) {
            return new JsonResponse(['message' => 'Le code d\'accès est invalide !'], 404);
        }

        // On regarde si il est déja dans le foyer
        $IsAlreadyInHouseHold = $this->houseHoldService->checkPersonInHouseHold($person->getId(),$houseHold->getId());
        if($IsAlreadyInHouseHold){
            return new JsonResponse(['message' => 'Vous appartenez déja à ce foyer !'],404);
        }

        // On crée l'entité PersonHouseHold
        $PersonHousehold = new PersonHousehold();
        $PersonHousehold->setRole(HouseHoldEnum::MEMBER)->setHouseHold($houseHold)->setPerson($person);

        // On persiste les données
        $this->em->persist($PersonHousehold);
        $this->em->flush();

        return new JsonResponse ([
            'message' => 'Vous avez rejoint le foyer avec succès !',
            'name' => $houseHold->getName()
        ],200);
    }

    #[Route('/api/AddPeopleHouseHold', name: 'api_AddPeopleHouseHold', methods: 'POST')]
    public function AddPeopleToHouseHold(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        $firstname = $data['name'] ?? null;
        $lastname = $data['lastName'] ?? null;
        $email = $data['email'] ?? null;
        $personRole = $data['role'];
        $accessCode = $data['accessCode'];

        // On récupére l'utilisateur
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $person = $user->getPerson();

        // On vérifie que le foyer existe
        $houseHold = $this->houseHoldService->HouseHoldExist($accessCode);
        if(!$houseHold) {
            return new JsonResponse(['message' => 'Le code d\'accès est invalide !'], 404);
        }

        // On vérifie que l'utilisateur a les droits pour ajouter un membre au foyer
        $isHouseHoldAdmin = $this->houseHoldService->checkHouseHoldAdmin($person->getId(),$accessCode);
        if(!$isHouseHoldAdmin){
            return new JsonResponse(['message' => 'Vous n\'avez pas les droits !'],404);
        }

        // on créer la person selon les donées reçues
        $addPerson = $this->houseHoldService->addPersonToHousehold(
            $houseHold,
            $firstname,
            $lastname,
            $personRole,
            $email
        );
        if(!$addPerson){
            return new JsonResponse(['message' => 'L\'utilisateur n\'existe pas'], 404);
        }

        return new JsonResponse ([
            'message' => 'L\'utilisateur a été ajouté au foyer avec succès !'
        ],200);
    }

    #[Route('/api/DeletePeopleHouseHold/{id}', name: 'api_DeletePeopleHouseHold', methods: 'POST')]
    public function DeletePeopleToHouseHold(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $accessCode = $data['accessCode'];

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $person = $user->getPerson();

        // On vérifie que le foyer existe
        $houseHold = $this->houseHoldService->HouseHoldExist($accessCode);
        if(!$houseHold) {
            return new JsonResponse(['message' => 'Le code d\'accès est invalide !'], 404);
        }

        // On vérifie que l'utilisateur a les droits pour supprimer un membre au foyer
        $isHouseHoldAdmin = $this->houseHoldService->checkHouseHoldAdmin($person->getId(),$accessCode);
        if(!$isHouseHoldAdmin){
            return new JsonResponse(['message' => 'Vous n\'avez pas les droits !'],404);
        }

        // On supprime la personne du foyer
        $personHouseHoldToDelete = $this->em->getRepository(PersonHousehold::class)->findOneBy(['person' => $id, 'household' => $houseHold->getId()]);
        if(!$personHouseHoldToDelete){
            return new JsonResponse(['message' => 'La personne n\'appartient pas à ce foyer !'], 404);
        }

        $this->em->remove($personHouseHoldToDelete);
        $this->em->flush();

        return new JsonResponse ([
            'message' => 'L\'utilisateur a été supprimé du foyer avec succès !'
        ],200);
    }

    #[Route('/api/modifyPeopleHouseHold/{id}', name: 'api_ModifyPeopleHouseHold', methods: 'POST')]
    public function ModifyPeopleToHouseHold(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $accessCode = $data['accessCode'];
        $newRole = $data['role'];

        // On récupére l'utilisateur
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $person = $user->getPerson();

        // On vérifie que le foyer existe
        $houseHold = $this->houseHoldService->HouseHoldExist($accessCode);
        if(!$houseHold) {
            return new JsonResponse(['message' => 'Le code d\'accès est invalide !'], 404);
        }

        // On vérifie que l'utilisateur a les droits pour supprimer un membre au foyer
        $isHouseHoldAdmin = $this->houseHoldService->checkHouseHoldAdmin($person->getId(),$accessCode);
        if(!$isHouseHoldAdmin){
            return new JsonResponse(['message' => 'Vous n\'avez pas les droits !'],404);
        }

        // On modifie le rôle de la personne dans le foyer
        $personHouseHoldToModify = $this->em->getRepository(PersonHousehold::class)->findOneBy(['person' => $id, 'household' => $houseHold->getId()]);
        if(!$personHouseHoldToModify){
            return new JsonResponse(['message' => 'La personne n\'appartient pas à ce foyer !'], 404);
        }

        $personHouseHoldToModify->setRole(HouseHoldEnum::from($newRole));
        $this->em->persist($personHouseHoldToModify);
        $this->em->flush();

        return new JsonResponse ([
            'message' => 'Le rôle de l\'utilisateur a été modifié avec succès !'
        ],200);
    }
}
