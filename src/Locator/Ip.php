<?php

declare(strict_types=1);

namespace App\Locator;

use InvalidArgumentException;

final readonly class Ip
{
    private string $value;

    public function __construct(string $ip)
    {
        if (empty($ip)) {
            throw new InvalidArgumentException('Empty IP');
        }

        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            throw new InvalidArgumentException('Invalid IP ' . $ip);
        }

        $this->value = $ip;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
