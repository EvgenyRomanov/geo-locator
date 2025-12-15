<?php

declare(strict_types=1);

namespace App\Locator;

/**
 * Локатор, представляющий собой цепочку локаторов.
 * В данном случае представлен вариант, при котором цепочка локаторов вызывается до тех пор, пока
 * не будет получен ответ отличный от null.
 */
final readonly class ChainLocator implements Locator
{
    /** @var Locator[]  */
    private array $locators;

    public function __construct(Locator ...$locators)
    {
        $this->locators = $locators;
    }

    public function locate(Ip $ip): ?Location
    {
        foreach ($this->locators as $locator) {
            $location = $locator->locate($ip);

            if ($location !== null) {
                return $location;
            }
        }

        return null;
    }
}
