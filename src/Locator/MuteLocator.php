<?php

declare(strict_types=1);

namespace App\Locator;

use App\RsrErrorHandler;
use RuntimeException;

/**
 * Локатор, который подавляет ошибки, передавая их обработчику.
 */
final readonly class MuteLocator implements Locator
{
    public function __construct(private Locator $next, private RsrErrorHandler $handler) {}

    public function locate(Ip $ip): ?Location
    {
        try {
            return $this->next->locate($ip);
        } catch (RuntimeException $exception) {
            $this->handler->handle($exception);
            return null;
        }
    }
}
