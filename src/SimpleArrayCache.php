<?php

declare(strict_types=1);

namespace App;

use DateInterval;
use DateTime;
use InvalidArgumentException;
use LogicException;
use Psr\SimpleCache\CacheInterface;

final class SimpleArrayCache implements CacheInterface
{
    /**
     * Хранилище данных в памяти
     *
     * @var array<string, array{value: mixed, expires: ?int}>
     */
    private array $storage = [];

    public function delete(string $key): bool
    {
        throw new LogicException('Method not implemented');
    }

    public function clear(): bool
    {
        throw new LogicException('Method not implemented');
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        throw new LogicException('Method not implemented');
    }

    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        throw new LogicException('Method not implemented');
    }

    public function deleteMultiple(iterable $keys): bool
    {
        throw new LogicException('Method not implemented');
    }

    public function has(string $key): bool
    {
        throw new LogicException('Method not implemented');
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $this->validateKey($key);

        // Проверяем существование ключа
        if (!isset($this->storage[$key])) {
            return $default;
        }

        $item = $this->storage[$key];

        // Проверяем срок действия
        if ($item['expires'] !== null && $item['expires'] < time()) {
            // Удаляем просроченный элемент
            unset($this->storage[$key]);
            return $default;
        }

        return $item['value'];
    }

    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        $this->validateKey($key);

        // Конвертируем TTL в секунды
        $expires = $this->convertTtlToExpires($ttl);

        $this->storage[$key] = [
            'value' => $value,
            'expires' => $expires,
        ];

        return true;
    }

    /**
     * Конвертировать TTL в timestamp истечения срока
     *
     */
    private function convertTtlToExpires(int|DateInterval|null $ttl): ?int
    {
        if ($ttl === null) {
            // Бессрочное хранение
            return null;
        }

        if (is_int($ttl)) {
            // TTL уже в секундах
            return $ttl > 0 ? time() + $ttl : null;
        }

        // Конвертируем DateInterval в секунды
        $expires = (new DateTime())->add($ttl);

        return $expires->getTimestamp();
    }

    /**
     * Проверить валидность ключа
     *
     * @throws InvalidArgumentException
     */
    private function validateKey(string $key): void
    {
        if ($key === '') {
            throw new InvalidArgumentException('Cache key cannot be empty');
        }

        // Проверяем на недопустимые символы по PSR
        if (preg_match('/[{}()\/\\\\@:]/', $key)) {
            throw new InvalidArgumentException(
                'Cache key contains invalid characters: {}()/\\@:'
            );
        }
    }
}
