<?php

declare(strict_types=1);

namespace App\Locator;

use Psr\Http\Client\ClientInterface;

/**
 * Условный локатор (заглушка), который предоставляет информацию отличную от гео, например, информацию от сервиса whois.
 * @psalm-suppress UnusedProperty
 */
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
