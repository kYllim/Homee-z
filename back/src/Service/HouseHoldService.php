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

    public function HouseHoldExist(
        ?string $accessCode = null,
        ?string $name = null
    ): Household|false {

        if ($name !== null) {
            return $this->em
                ->getRepository(Household::class)
                ->findOneBy(['name' => $name]) ?: false;
        }

        if ($accessCode !== null) {
            return $this->em
                ->getRepository(Household::class)
                ->findOneBy(['accessCode' => $accessCode]) ?: false;
        }

        return false;
    }

    public function checkHouseHoldAdmin(int $id,string $AccesCode){
        $houseHold = $this->em->getRepository(Household::class)->findOneBy(['accessCode' => $AccesCode]);
        $members = $houseHold->getMemberships();
        foreach ($members as $member) {
            $idMember = $member->getPerson()->getId();
            if( $id === $idMember && $member->getRole() !== HouseHoldEnum::ADMIN){
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

    public function addPersonToHousehold(Household $houseHold,string $personRole, ?string $firstname = null,
    ?string $lastname = null,?string $email = null){

        if($firstname === null && $lastname === null && $email === null){
            return false;
        }

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

        // On check si l'utilisateur fait déjà partie du foyer
        $existingMembership = $this->checkPersonInHouseHold($user->getPerson()->getId(), $houseHold->getId());
        if ($existingMembership) {
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
