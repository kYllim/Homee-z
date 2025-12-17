<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\HouseHoldService;
use App\Entity\Household;
use App\Entity\Person;
use App\Entity\PersonHousehold;
use App\Enum\HouseHoldEnum;
use App\Entity\User;
use App\Enum\PersonEnum;
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

        // On récupére l'utilisateur et le nom
        $houseHoldName = $data['name'];
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $person = $user->getPerson();

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
        $houseHold = $this->em->getRepository(Household::class)->findOneBy(['accessCode' => $accessCode]);
        if(!$houseHold) {
            return new JsonResponse(['message' => 'Le code d\'accès est invalide !'], 404);
        }

        // On regarde si il est déja dans le foyer
        $houseHoldMembers = $houseHold->getMemberships();
        foreach ($houseHoldMembers as $member) {
            $id = $member->getPerson()->getId();
            if($person->getId() === $id){
                return new JsonResponse(['message' => 'Vous appartenez déja à ce foyer !'],404);
            }
        }

        // On crée l'entité PersonHouseHold
        $PersonHousehold = new PersonHousehold();
        $PersonHousehold->setRole(HouseHoldEnum::MEMBER)->setHouseHold($houseHold)->setPerson($person);

        // On persiste les données
        $this->em->persist($PersonHousehold);
        $this->em->flush();

        return new JsonResponse ([
            'message' => 'Vous avez rejoint le foyer avec succès !'
        ],200);
    }

    #[Route('/api/AddPeopleHouseHold', name: 'api_AddPeopleHouseHold', methods: 'POST')]
    public function AddPeopleToHouseHold(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        // On récupére l'utilisateur et le code d'accès
        $firstname = $data['name'];
        $lastname = $data['lastName'];
        $email = $data['email'] ?? null;
        $personRole = $data['role'];
        $accessCode = $data['accessCode'];

        // On récupére l'utilisateur
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $person = $user->getPerson();

        // On vérifie que le foyer existe
        $houseHold = $this->em->getRepository(Household::class)->findOneBy(['accessCode' => $accessCode]);
        if(!$houseHold) {
            return new JsonResponse(['message' => 'Le code d\'accès est invalide !'], 404);
        }

        // On vérifie que l'utilisateur a les droits pour ajouter un membre au foyer
        $houseHoldMembers = $houseHold->getMemberships();
        foreach ($houseHoldMembers as $member) {
            $id = $member->getPerson()->getId();
            if($person->getId() === $id && $member->getRole() !== HouseHoldEnum::ADMIN){
                return new JsonResponse(['message' => 'Vous ne pouvez pas ajouter un membre au foyer !'],404);
            }
        }

        // on créer la person selon les donées reçues
        if(is_null($email)){
            // on créer une personne en tant qu'enfant
            $newChildPerson = new Person();
            $newPersonHousehold = new PersonHousehold();
            $newChildPerson->setFirstName($firstname)->setLastName($lastname)->setUserType(PersonEnum::Child);
            $newPersonHousehold->setHousehold($houseHold)->setPerson($newChildPerson)->setRole(HouseHoldEnum::from($personRole));

            $this->em->persist($newChildPerson);
            $this->em->persist($newPersonHousehold);
            $this->em->flush();

        }
        else{
            // on créer regarde si la personne  est bien utilisateur
            $userToSearch = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
            if(!$userToSearch){
                return new JsonResponse(['message' => 'L\'utilisateur n\'existe pas !'], 404);
            }

            $personToAdd = $userToSearch->getPerson();
            $newPersonHousehold = new PersonHousehold();
            $newPersonHousehold->setHousehold($houseHold)->setPerson($personToAdd)->setRole(HouseHoldEnum::from($personRole));

            $this->em->persist($newPersonHousehold);
            $this->em->flush();

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

        // On récupére l'utilisateur
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $person = $user->getPerson();

        // On vérifie que le foyer existe
        $houseHold = $this->em->getRepository(Household::class)->findOneBy(['accessCode' => $accessCode]);
        if(!$houseHold) {
            return new JsonResponse(['message' => 'Le code d\'accès est invalide !'], 404);
        }

        // On vérifie que l'utilisateur a les droits pour supprimer un membre au foyer
        $houseHoldMembers = $houseHold->getMemberships();
        foreach ($houseHoldMembers as $member) {
            $id = $member->getPerson()->getId();
            if($person->getId() === $id && $member->getRole() !== HouseHoldEnum::ADMIN){
                return new JsonResponse(['message' => 'Vous ne pouvez pas supprimer un membre du foyer !'],404);
            }
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
        $houseHold = $this->em->getRepository(Household::class)->findOneBy(['accessCode' => $accessCode]);
        if(!$houseHold) {
            return new JsonResponse(['message' => 'Le code d\'accès est invalide !'], 404);
        }

        // On vérifie que l'utilisateur a les droits pour modifier un membre au foyer
        $houseHoldMembers = $houseHold->getMemberships();
        foreach ($houseHoldMembers as $member) {
            $id = $member->getPerson()->getId();
            if($person->getId() === $id && $member->getRole() !== HouseHoldEnum::ADMIN){
                return new JsonResponse(['message' => 'Vous ne pouvez pas modifier un membre du foyer !'],404);
            }
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
