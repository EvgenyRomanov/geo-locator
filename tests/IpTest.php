<?php

declare(strict_types=1);

namespace Tests;

use App\Locator\Ip;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Ip::class)]
final class IpTest extends TestCase
{
    public function testIp4(): void
    {
        $ip = new Ip($value = "8.8.8.8");
        self::assertEquals($value, $ip->getValue());
    }

    public function testIp6(): void
    {
        $ip = new Ip($value = "0:0:0:0:0:0:0:1");
        self::assertEquals($value, $ip->getValue());
    }

    public function testInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Ip("incorrect");
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Ip("");
    }
}
