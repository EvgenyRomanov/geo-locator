<?php

declare(strict_types=1);

namespace Tests;

use App\Locator\ChainLocator;
use App\Locator\Ip;
use App\Locator\Location;
use App\Locator\Locator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ChainLocator::class)]
final class ChainLocatorTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testSuccess(): void
    {
        $locators = [
            $this->mockLocator(null),
            $this->mockLocator($expected = new Location('Expected')),
            $this->mockLocator(null),
            $this->mockLocator(new Location('Other')),
            $this->mockLocator(null),
        ];

        $locator = new ChainLocator(...$locators);
        $actual = $locator->locate(new Ip("8.8.8.8"));

        self::assertNotNull($actual);
        self::assertEquals($expected, $actual);
    }

    /**
     * @throws Exception
     */
    private function mockLocator(?Location $location): Locator
    {
        $mock = $this->createMock(Locator::class);
        $mock->method('locate')->willReturn($location);
        return $mock;
    }
}
