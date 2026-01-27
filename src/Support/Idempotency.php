<?php declare(strict_types=1);

namespace PayKit\Support;

use PayKit\Payload\Common\Reference;

final class Idempotency
{
    public const HEADER = 'Idempotency-Key';

    /**
     * Builds a stable idempotency key thatâ€™s safe to send to providers.
     * Keep it short-ish and deterministic.
     */
    public static function key(string $driverKey, string $operation, Reference|string $reference, ?string $salt = null, int $maxLen = 64): string
    {
        $ref = $reference instanceof Reference ? $reference->toString() : (string)$reference;
        $base = strtolower($driverKey . ':' . $operation . ':' . $ref . ($salt ? ':' . $salt : ''));

        // Hash to avoid leaking internal references to providers + keep length bounded
        $hash = hash('sha256', $base);

        $key = strtolower($driverKey) . ':' . substr($hash, 0, max(8, $maxLen - (strlen($driverKey) + 1)));
        return substr($key, 0, $maxLen);
    }
}

