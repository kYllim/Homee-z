<?php

namespace App\Service;

class HouseHoldService {

    public function __construct()
    {

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
}
