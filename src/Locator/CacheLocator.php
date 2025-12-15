<?php

declare(strict_types=1);

namespace App\Locator;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Локатор, сохраняющий ответы в кэш.
 */
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
     */
    public function locate(Ip $ip): ?Location
    {
        $key = "location-{$this->prefix}" . $ip->getValue();
        /** @var Location|null $location */
        $location = $this->cache->get($key);

        if ($location === null) {
            $location = $this->next->locate($ip);
            $this->cache->set($key, $location, $this->ttl);
        }

        return $location;
    }
}
