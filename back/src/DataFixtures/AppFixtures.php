<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Person;
use App\Entity\Household;
use App\Entity\Event;
use App\Entity\Chore;
use App\Entity\PersonHousehold;
use App\Enum\PersonEnum;
use App\Enum\HouseHoldEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Créer un foyer (household)
        $household = new Household();
        $household->setName('Maison de la famille Dupont');
        $household->setAccessCode('HOMEE2024');
        $manager->persist($household);

        // Créer des utilisateurs
        $users = [];
        $persons = [];
        $userData = [
            [
                'email' => 'alice@example.com',
                'firstName' => 'Alice',
                'lastName' => 'Dupont',
                'password' => 'Password123!'
            ],
            [
                'email' => 'bob@example.com',
                'firstName' => 'Bob',
                'lastName' => 'Dupont',
                'password' => 'Password123!'
            ],
            [
                'email' => 'charlie@example.com',
                'firstName' => 'Charlie',
                'lastName' => 'Dupont',
                'password' => 'Password123!'
            ]
        ];

        foreach ($userData as $data) {
            $person = new Person();
            $person->setFirstName($data['firstName']);
            $person->setLastName($data['lastName']);
            $person->setUserType(PersonEnum::Adult);
            $manager->persist($person);
            $persons[] = $person;

            $user = new User();
            $user->setEmail($data['email']);
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
            $user->setPerson($person);
            $user->setIsVerified(true);
            $manager->persist($user);
            $users[] = $user;

            // Ajouter chaque utilisateur au foyer
            $personHousehold = new PersonHousehold();
            $personHousehold->setPerson($person);
            $personHousehold->setHousehold($household);
            $personHousehold->setRole(HouseHoldEnum::MEMBER);
            $manager->persist($personHousehold);
        }

        // Créer des événements
        $now = new \DateTime('now');
        $eventTitles = [
            'Réunion de famille',
            'Nettoyage de la maison',
            'Dîner en famille',
            'Shopping groceries',
            'Anniversaire de Alice',
            'Maintenance de la voiture',
            'Pique-nique au parc'
        ];

        foreach ($eventTitles as $index => $title) {
            $event = new Event();
            $event->setTitle($title);
            $event->setDescription('Description de ' . $title);
            $event->setCreator($persons[$index % count($persons)]);
            $event->setHousehold($household);
            $event->setType('event');
            $event->setStatus('planned');
            
            // Répartir les événements sur les prochains 30 jours
            $startDate = clone $now;
            $startDate->modify('+' . ($index * 4) . ' days');
            $endDate = clone $startDate;
            $endDate->modify('+2 hours');
            
            $event->setStartAt($startDate);
            $event->setEndAt($endDate);
            
            $manager->persist($event);
        }

        // Créer des corvées
        $now = new \DateTime('now');
        $choreTitles = [
            'Faire la vaisselle',
            'Passer l\'aspirateur',
            'Sortir les poubelles',
            'Faire les courses',
            'Nettoyer la salle de bain',
            'Tondre la pelouse',
            'Arroser les plantes'
        ];

        foreach ($choreTitles as $index => $title) {
            $chore = new Chore();
            $chore->setTitle($title);
            $chore->setDescription('Description de ' . $title);
            $chore->setCreator($persons[$index % count($persons)]);
            $chore->setHousehold($household);
            
            // Répartir les corvées sur les prochains 14 jours
            $startDate = clone $now;
            $startDate->modify('+' . ($index * 2) . ' days');
            $endDate = clone $startDate;
            $endDate->modify('+1 day');
            
            $chore->setStartAt($startDate);
            $chore->setEndAt($endDate);
            $chore->setStatus('pending');
            $chore->setCreatedAt(new \DateTimeImmutable());
            
            $manager->persist($chore);
        }

        $manager->flush();

        echo "✓ Fixtures créées avec succès!\n";
        echo "  - 1 foyer\n";
        echo "  - " . count($users) . " utilisateurs\n";
        echo "  - " . count($eventTitles) . " événements\n";
        echo "  - " . count($choreTitles) . " corvées\n";
        echo "\nIdentifiants de connexion:\n";
        foreach ($userData as $data) {
            echo "  Email: {$data['email']}, Mot de passe: {$data['password']}\n";
        }
    }
}
