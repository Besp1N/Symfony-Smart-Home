<?php

namespace App\Interfaces;


interface DeviceInterface
{
    public function checkDeviceOwner(): bool;
}