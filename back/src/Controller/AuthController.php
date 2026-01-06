<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Person;
use App\Enum\PersonEnum;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Validation\Validator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

final class AuthController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;
    private ValidatorInterface $validator;
    private JWTTokenManagerInterface $jwtManager;
    private MailerInterface $mailer;

    public function __construct (EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validator, JWTTokenManagerInterface $jwtManager, MailerInterface $mailer) {
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
        $this->validator = $validator;
        $this->jwtManager = $jwtManager;
        $this->mailer = $mailer;
    }

    #[Route('/api/register', name: 'api_register', methods: 'POST')]
    public function Register(Request $request): JsonResponse
    {
        // On récupére ici le corp de la requête
        $data = json_decode($request->getContent(),true);

        // Ensuite ici on recupérer les éléments pour les vérifier
        $email = $data['email'];
        $firstName = $data['name'];
        $lastName = $data['firstname'];
        $password = $data['password'];

        // On vérifie dans un premier temps que l'email est unique
        $IsEmailExist = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if($IsEmailExist) {
            return new JsonResponse(['message' => 'email déja utilisé ou une erreur est survenue !'], Response::HTTP_CONFLICT);
        }

        // Ensuite on va créer un nouvel utilisateur et lui sette les valeurs
        $user = new User();
        $person = New Person();
        $VerificationToken = Uuid::v4();
        $verifyUrl = "http://localhost:8000/api/verifyEmail?token=" . $VerificationToken;

        // On hash le mot de passe avant de le stocker
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);

        // on ajoute la person dans user
        $person->setFirstName($firstName)->setLastName($lastName)->setUserType(PersonEnum::Adult);

        $user->setEmail($email)->setFirstName($firstName)->setLastName($lastName)->setPassword($hashedPassword)->setVerificationToken($VerificationToken)->setPerson($person);


        // On valide l'utilisateur avec les contraintes de l'entité User
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse(['message' => $errorsString], Response::HTTP_BAD_REQUEST);
        }

        // On insére l'utilisateur en base de données et lui retourne le token JWT
        $this->em->persist($user);
        $this->em->flush();

        $email = (new Email())
            ->from('no-reply@homeez.fr')
            ->to($email)
            ->subject('Vérification de votre adresse e-mail')
            ->html("<p>Bienvenue, {$user->getFirstName()} !</p>
                <p>Clique sur le lien ci-dessous pour confirmer ton e-mail :</p>
                <a href='{$verifyUrl}'>Confirmer mon compte</a>"
            );

        $this->mailer->send($email);

        // On génère le token JWT pour l'utilisateur
        $jwtToken = $this->jwtManager->create($user);

        // On renvoie la réponse de succès
        $response = new JsonResponse(
            [
                'token' => $jwtToken,
                'status' => "succes"
            ], Response::HTTP_CREATED
        );

        // On sécurise le token avec les options de cookie
        return $response;

    }

    #[Route('/api/verifyEmail', name: 'api_verifyEmail', methods: 'GET')]
    public function VerifyEmail(Request $request): JsonResponse
    {
        $token = $request->query->get('token');

        // On vérifie que le token est présent
        if (!$token) {
            return $this->json(['error' => 'Token manquant'], 400);
        }

        // On cherche l'utilisateur avec le token corrrespondant
        $user = $this->em->getRepository(User::class)->findOneBy(['VerificationToken' => $token]);

        if (!$user) {
            return $this->json(['error' => 'Token invalide'], 400);
        }

        // On modifie l'utilisateur pour qu'il soit vérifié et on reset le token
        $user->setIsVerified(true);
        $user->setVerificationToken(null);
        $this->em->flush();

        // On génère le token JWT pour l'utilisateur
        $jwtToken = $this->jwtManager->create($user);

        // On renvoie la réponse de succès
        $response = new JsonResponse(['token' => $jwtToken], Response::HTTP_CREATED);

        // On sécurise le token avec les options de cookie
        $response->headers->setCookie(
        Cookie::create('BEARER', $jwtToken)
            ->withHttpOnly(true)
            ->withSecure(true)
            ->withSameSite('Strict')
            ->withPath('/')
        );

        return $response;

    }
}
