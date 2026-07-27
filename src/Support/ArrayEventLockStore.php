<?php declare(strict_types=1);

namespace PayKit\Support;

final class ArrayEventLockStore implements EventLockStoreInterface
{
    /** @var array<string, int> */
    private array $store = [];

    public function has(string $key): bool
    {
        if (!isset($this->store[$key])) {
            return false;
        }

        if (time() > $this->store[$key]) {
            unset($this->store[$key]);
            return false;
        }

        return true;
    }

    public function put(string $key, int $ttlSeconds = 86400): void
    {
        $this->store[$key] = time() + $ttlSeconds;
    }

    public function forget(string $key): void
    {
        unset($this->store[$key]);
    }
}
