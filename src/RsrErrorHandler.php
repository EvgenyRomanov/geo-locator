<?php

declare(strict_types=1);

namespace App;

use Exception;

final readonly class RsrErrorHandler implements ErrorHandler
{
    public function __construct(private \Psr\Log\LoggerInterface $logger) {}

    public function handle(Exception $exception): void
    {
        $this->logger->error($exception->getMessage(), [
            'exception' => $exception,
        ]);
    }
}
