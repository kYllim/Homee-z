<?php

namespace App\Service;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Household;
use App\Entity\PersonHousehold;
use App\Enum\HouseHoldEnum;
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
        return true;
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
        $personHouseHoldToModify = $this->em->getRepository(PersonHousehold::class)->findOneBy(['person' => $personId, 'household' => $HouseHoldId]);
        if(!$personHouseHoldToModify){
            return false;
        }
        return true;
    }
}
