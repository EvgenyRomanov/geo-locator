<?php

declare(strict_types=1);

namespace App;

use Exception;

interface ErrorHandler
{
    public function handle(Exception $exception): void;
}
