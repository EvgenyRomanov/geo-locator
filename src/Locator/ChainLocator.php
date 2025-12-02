<?php

declare(strict_types=1);

namespace App\Locator;

final readonly class ChainLocator implements Locator
{
    private array $locators;

    public function __construct(Locator ...$locators)
    {
        $this->locators = $locators;
    }

    public function locate(Ip $ip): ?Location
    {
        /** @psalm-suppress MixedAssignment */
        foreach ($this->locators as $locator) {
            /** @psalm-suppress MixedMethodCall */
            $location = $locator->locate($ip);

            if ($location !== null) {
                /** @psalm-suppress MixedReturnStatement */
                return $location;
            }
        }

        return null;
    }
}
