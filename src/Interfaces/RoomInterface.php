<?php

namespace App\Interfaces;


use App\Entity\House;
use App\Entity\Room;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

interface RoomInterface
{
    public function roomServiceDelete(Request $request): void;
    public function roomServiceAdd(Request $request): void;
    public function checkRoomOwner(House $house, User $user): bool;
}