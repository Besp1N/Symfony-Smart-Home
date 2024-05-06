<?php

namespace App\Interfaces;


use App\Entity\Room;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

interface RoomInterface
{
    public function roomServiceDelete(Request $request): void;
    public function roomServiceAdd(Request $request): void;
    public function checkRoomOwner(Room $room, User $user): bool;
}