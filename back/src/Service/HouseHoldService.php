<?php

namespace App\Service;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Household;
use App\Entity\PersonHousehold;
use App\Enum\HouseHoldEnum;
use App\Enum\PersonEnum;
use App\Entity\Person;
use App\Entity\User;

class HouseHoldService {

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createHouseHoldCode(): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%^&*';
        $codeLength = 8;
        $accessCode = '';

        for ($i = 0; $i < $codeLength; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $accessCode .= $characters[$index];
        }

        return $accessCode;
    }

    public function HouseHoldExist(string $AccesCode){
        $houseHold = $this->em->getRepository(Household::class)->findOneBy(['accessCode' => $AccesCode]);
        if(!$houseHold) {
            return false;
        }
        return $houseHold;
    }

    public function checkHouseHoldAdmin(int $id,string $AccesCode){
        $houseHold = $this->em->getRepository(Household::class)->findOneBy(['accessCode' => $AccesCode]);
        $members = $houseHold->getMemberships();
        foreach ($members as $member) {
            $id = $member->getPerson()->getId();
            if( $id=== $id && $member->getRole() !== HouseHoldEnum::ADMIN){
                return false;
            }
        }

        return true;
    }

    public function checkPersonInHouseHold(int $personId,int $HouseHoldId){
        $personHouseHold = $this->em->getRepository(PersonHousehold::class)->findOneBy(['person' => $personId, 'household' => $HouseHoldId]);
        if(!$personHouseHold){
            return false;
        }
        return true;
    }

    public function addPersonToHousehold(Household $houseHold,string $firstname,
    string $lastname,string $personRole,?string $email = null){

        // Cas 1 : enfant (pas d'email)
        if ($email === null) {
            $childPerson = new Person();
            $childPerson
                ->setFirstName($firstname)
                ->setLastName($lastname)
                ->setUserType(PersonEnum::Child);

            $personHousehold = new PersonHousehold();
            $personHousehold
                ->setHousehold($houseHold)
                ->setPerson($childPerson)
                ->setRole(HouseHoldEnum::from($personRole));

            $this->em->persist($childPerson);
            $this->em->persist($personHousehold);
            $this->em->flush();

            return $personHousehold;
        }

        // Cas 2 : utilisateur existant
        $user = $this->em
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if (!$user) {
            return false;
        }

        $personHousehold = new PersonHousehold();
        $personHousehold
            ->setHousehold($houseHold)
            ->setPerson($user->getPerson())
            ->setRole(HouseHoldEnum::from($personRole));

        $this->em->persist($personHousehold);
        $this->em->flush();

        return $personHousehold;
    }
}
