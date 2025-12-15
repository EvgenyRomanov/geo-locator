<?php

declare(strict_types=1);

namespace App;

use Psr\Log\LoggerInterface;
use Stringable;

final readonly class Logger implements LoggerInterface
{
    public function emergency(Stringable|string $message, array $context = []): void
    {
        echo (string) $message . PHP_EOL;
    }

    public function alert(Stringable|string $message, array $context = []): void
    {
        echo (string) $message . PHP_EOL;
    }

    public function critical(Stringable|string $message, array $context = []): void
    {
        echo (string) $message . PHP_EOL;
    }

    public function error(Stringable|string $message, array $context = []): void {}

    public function warning(Stringable|string $message, array $context = []): void
    {
        echo (string) $message . PHP_EOL;
    }

    public function notice(Stringable|string $message, array $context = []): void
    {
        echo (string) $message . PHP_EOL;
    }

    public function info(Stringable|string $message, array $context = []): void
    {
        echo (string) $message . PHP_EOL;
    }

    public function debug(Stringable|string $message, array $context = []): void
    {
        echo (string) $message . PHP_EOL;
    }

    public function log($level, Stringable|string $message, array $context = []): void
    {
        echo (string) $message . PHP_EOL;
    }
}
