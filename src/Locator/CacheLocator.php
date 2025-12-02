<?php

declare(strict_types=1);

namespace App\Locator;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

final readonly class CacheLocator implements Locator
{
    public function __construct(
        private Locator          $next,
        private CacheInterface $cache,
        private string           $prefix,
        private int              $ttl
    ) {}

    /**
     * @throws InvalidArgumentException
     * @psalm-suppress PossiblyUnusedReturnValue
     */
    public function locate(Ip $ip): ?Location
    {
        $key = "location-{$this->prefix}" . $ip->getValue();
        /** @psalm-suppress MixedAssignment */
        $location = $this->cache->get($key);

        if ($location === null) {
            $location = $this->next->locate($ip);
            $this->cache->set($key, $location, $this->ttl);
        }

        /** @psalm-suppress MixedReturnStatement */
        return $location;
    }
}
