<?php

declare(strict_types=1);

namespace App\Locator;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Slim\Psr7\Factory\RequestFactory;

/**
 * Типовой локатор.
 */
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
        /** @var array{location: array{country_name: string, state_prov: string, city: string}}|null $data */
        $data = json_decode((string) $response->getBody(), true);

        if (is_null($data) || !isset($data['location'])) {
            return null;
        }

        $data = $data['location'];

        if ($data['country_name'] === "") {
            return null;
        }

        return new Location($data['country_name'], $data['state_prov'], $data['city']);
    }
}
