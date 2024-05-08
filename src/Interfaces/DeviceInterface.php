<?php

namespace App\Interfaces;


use App\Entity\Device;
use App\Entity\Room;
use App\Entity\User;

interface DeviceInterface
{
    public function checkDeviceOwner(Room $room, User $user): bool;
}