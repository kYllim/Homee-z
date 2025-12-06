<?php

namespace App\Enum;

enum HouseHoldEnum : string {
    case Admin = 'Admin';
    case User_Child = 'User_Child';
    case User_Adult = 'User_Adult';
}
