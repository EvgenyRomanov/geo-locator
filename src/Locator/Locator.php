<?php

declare(strict_types=1);

namespace App\Locator;

interface Locator
{
    public function locate(Ip $ip): ?Location;
}
