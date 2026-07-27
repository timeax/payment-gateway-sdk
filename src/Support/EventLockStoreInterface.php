<?php declare(strict_types=1);

namespace PayKit\Support;

interface EventLockStoreInterface
{
    public function has(string $key): bool;

    public function put(string $key, int $ttlSeconds = 86400): void;

    public function forget(string $key): void;
}
