<?php

namespace App\Interfaces;


use App\Entity\Room;
use App\Entity\User;

interface CheckRoomOwnerInterface
{
    public function checkRoomOwner(Room $room, User $user): bool;
}