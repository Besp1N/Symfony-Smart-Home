<?php

namespace App\Interfaces;


use App\Entity\Device;
use App\Entity\Room;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

interface DeviceInterface
{
    public function deviceServiceAdd(Request $request): void;
    public function deviceServiceDelete(Request $request): void;
    public function checkDeviceOwner(Room $room, User $user): bool;
}