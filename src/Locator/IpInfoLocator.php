<?php

declare(strict_types=1);

namespace App\Locator;

use Psr\Http\Client\ClientInterface;

/** @psalm-suppress UnusedProperty */
final readonly class IpInfoLocator implements Locator
{
    public function __construct(
        private ClientInterface $client,
        private string $apiKey
    ) {}

    public function locate(Ip $ip): ?Location
    {
        return null;
    }
}
