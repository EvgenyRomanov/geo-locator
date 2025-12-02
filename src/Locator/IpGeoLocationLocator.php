<?php

declare(strict_types=1);

namespace App\Locator;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Slim\Psr7\Factory\RequestFactory;

final readonly class IpGeoLocationLocator implements Locator
{
    public function __construct(
        private ClientInterface $client,
        private string $apiKey,
    ) {}

    /**
     * @throws ClientExceptionInterface
     */
    public function locate(Ip $ip): ?Location
    {
        $url = "https://api.ipgeolocation.io/v2/ipgeo?" . http_build_query(['ip' => $ip->getValue(), 'apiKey' => $this->apiKey]);
        $response = $this->client->sendRequest((new RequestFactory())->createRequest('GET', $url));
        /** @psalm-suppress MixedAssignment */
        $data = json_decode((string) $response->getBody(), true);
        /** @psalm-suppress MissingClosureParamType */
        /** @psalm-suppress MixedArrayAccess */
        /** @psalm-suppress MixedArgument */
        $data = is_null($data)
            ? []
            : array_map(static fn($value) => $value !== '-' ? $value : null, $data['location']);

        /** @psalm-suppress RiskyTruthyFalsyComparison */
        if (empty($data['country_name'])) {
            return null;
        }

        /** @psalm-suppress MixedArgument */
        return new Location($data['country_name'], $data['state_prov'], $data['city']);
    }
}
