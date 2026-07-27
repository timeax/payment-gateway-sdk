<?php declare(strict_types=1);

namespace PayKit\Support;

final class WebhookDeduplicator
{
    public function __construct(
        private readonly EventLockStoreInterface $store = new ArrayEventLockStore(),
    ) {}

    public function lockKey(string $driverKey, string $eventId): string
    {
        return "paykit:webhook_event:{$driverKey}:{$eventId}";
    }

    public function isDuplicate(string $driverKey, string $eventId): bool
    {
        return $this->store->has($this->lockKey($driverKey, $eventId));
    }

    public function markProcessed(string $driverKey, string $eventId, int $ttlSeconds = 86400): void
    {
        $this->store->put($this->lockKey($driverKey, $eventId), $ttlSeconds);
    }

    public function forget(string $driverKey, string $eventId): void
    {
        $this->store->forget($this->lockKey($driverKey, $eventId));
    }

    /**
     * Safely execute a webhook callback once per event ID.
     *
     * @template T
     * @param callable(): T $callback
     * @return T|null Returns callback result or null if duplicate
     */
    public function executeOnce(string $driverKey, string $eventId, callable $callback, int $ttlSeconds = 86400): mixed
    {
        if ($this->isDuplicate($driverKey, $eventId)) {
            return null;
        }

        $this->markProcessed($driverKey, $eventId, $ttlSeconds);

        try {
            return $callback();
        } catch (\Throwable $e) {
            // Un-lock on failure to allow retry processing
            $this->forget($driverKey, $eventId);
            throw $e;
        }
    }
}
