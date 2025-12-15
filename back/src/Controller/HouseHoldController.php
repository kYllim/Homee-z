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

        // On récupére l'utilisateur et le nom
        $houseHoldName = $data['name'];
        $user = $this->getUser();

        // On initialise les entités
        $PersonHousehold = new PersonHousehold();
        $houseHold = new Household();

        // On insére les données
        $AccesCode = $this->houseHoldService->createHouseHoldCode();
        $houseHold->setName($houseHoldName)->setAccessCode($AccesCode);

        // On va insérer l'utilisateur en tant que chef du foyer
        $PersonHousehold->setRole(HouseHoldEnum::ADMIN)->setHouseHold($houseHold)->setPerson($user);

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
        $user = $this->getUser();

        // On vérifie que le foyer existe
        $houseHold = $this->em->getRepository(Household::class)->findOneBy(['accessCode' => $accessCode]);
        if(!$houseHold) {
            return new JsonResponse(['message' => 'Le code d\'accès est invalide !'], 404);
        }

        // On crée l'entité UserHouseHold
        $PersonHousehold = new PersonHousehold();
        $PersonHousehold->setRole(HouseHoldEnum::MEMBER)->setHouseHold($houseHold)->setPerson($user);

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
        $user = $data['name'];
        $userRole = $data['role'];
        $accessCode = $data['accessCode'];

        // On vérifie que le foyer existe
        $houseHold = $this->em->getRepository(Household::class)->findOneBy(['accessCode' => $accessCode]);
        if(!$houseHold) {
            return new JsonResponse(['message' => 'Le code d\'accès est invalide !'], 404);
        }

        // On récupére l'utilisateur
        $userMail = $this->getUser()->getUserIdentifier();
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $userMail]);

        // On vérifie que l'utilisateur a les droits pour ajouter un membre au foyer
        $HouseHolds = ($user->getPerson())->getMemberships();
        $HouseHolder = null;
        foreach($HouseHolds as $HouseHold) {
            if($HouseHold->getHouseHold() === $houseHold) {
                if($HouseHold->getRole() !== HouseHoldEnum::ADMIN) {
                    return new JsonResponse(['message' => 'Vous n\'avez pas les droits pour ajouter un membre au foyer !'], 403);
                }
                $HouseHolder = $HouseHold->getHouseHold();
            }
        }

        return new JsonResponse ([
            'message' => 'L\'utilisateur a été ajouté au foyer avec succès !'
        ],200);
    }
}
