<?php declare(strict_types=1);

namespace PayKit\Drivers\Concerns;

use PayKit\Payload\Common\CanonicalPaymentStatus;

trait MapsStatuses
{
    /**
     * @param array<string,CanonicalPaymentStatus|string> $map
     */
    protected function mapStatusValue(string $providerStatus, array $map, CanonicalPaymentStatus $default): CanonicalPaymentStatus
    {
        $k = strtolower(trim($providerStatus));
        $v = $map[$k] ?? $map[$providerStatus] ?? null;

        if ($v instanceof CanonicalPaymentStatus) {
            return $v;
        }

        if (is_string($v)) {
            try {
                return CanonicalPaymentStatus::from($v);
            } catch (\Throwable) {
                return $default;
            }
        }

        return $default;
    }

    /**
     * Best-effort extraction from array/object payloads.
     * @param array<int,string> $keys
     */
    protected function extractString(mixed $payload, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (is_array($payload) && array_key_exists($key, $payload) && is_scalar($payload[$key])) {
                return (string) $payload[$key];
            }

            if (is_object($payload)) {
                if (isset($payload->{$key}) && is_scalar($payload->{$key})) {
                    return (string) $payload->{$key};
                }
                $getter = 'get' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));
                if (method_exists($payload, $getter)) {
                    $v = $payload->{$getter}();
                    if (is_scalar($v)) {
                        return (string) $v;
                    }
                }
            }
        }

        return null;
    }
}