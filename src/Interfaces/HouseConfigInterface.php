<?php

namespace App\Interfaces;


use App\Entity\House;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

interface HouseConfigInterface
{
    public function houseServiceAdd(Request $request): void;
    public function checkHouseOwner(House $house, User $user): bool;
}