<?php

declare(strict_types=1);

namespace Tests;

use App\Locator\Ip;
use App\Locator\IpGeoLocationLocator;
use App\Locator\Locator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Response;

/**
 * @internal
 */
#[CoversClass(Locator::class)]
final class LocatorTest extends TestCase
{
    private string $apiKey = 'api_key';

    /**
     * @throws Exception
     * @throws ClientExceptionInterface
     */
    public function testSuccess(): void
    {
        $client = $this->createMock(ClientInterface::class);
        /** @psalm-suppress PossiblyFalseArgument */
        $client->method('sendRequest')->willReturn(new Response(body: (new StreamFactory())->createStream(
            json_encode([
                'location' => [
                    'country_name' => 'United States',
                    'state_prov' => 'California',
                    'city' => 'Mountain View',
                ],
            ])
        )));

        $locator = new IpGeoLocationLocator($client, $this->apiKey);
        $location = $locator->locate(new Ip("8.8.8.8"));

        self::assertNotNull($location);

        self::assertEquals("United States", $location->getCountry());
        self::assertEquals("California", $location->getRegion());
        self::assertEquals("Mountain View", $location->getCity());
    }

    /**
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testNotFound(): void
    {
        $client = $this->createMock(ClientInterface::class);
        /** @psalm-suppress PossiblyFalseArgument */
        $client->method('sendRequest')->willReturn(new Response(body: (new StreamFactory())->createStream(
            json_encode([
                'location' => [
                    'country_name' => '-',
                    'state_prov' => '-',
                    'city' => '-',
                ],
            ])
        )));

        $locator = new IpGeoLocationLocator($client, $this->apiKey);
        $location = $locator->locate(new Ip("127.0.0.1"));

        self::assertNull($location);
    }
}
