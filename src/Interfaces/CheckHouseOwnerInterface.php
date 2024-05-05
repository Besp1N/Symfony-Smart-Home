<?php

namespace App\Interfaces;


use App\Entity\House;
use App\Entity\User;

interface CheckHouseOwnerInterface
{
    public function checkHouseOwner(House $house, User $user): bool;
}