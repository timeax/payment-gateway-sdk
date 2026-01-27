<?php declare(strict_types=1);

namespace PayKit\Support;

use JsonSerializable;

final class Redactor
{
    /** @var array<int,string> */
    private array $sensitiveKeys;

    public function __construct(?array $sensitiveKeys = null)
    {
        $this->sensitiveKeys = $sensitiveKeys ?? [
            'secret',
            'secrets',
            'api_key',
            'apikey',
            'key',
            'token',
            'access_token',
            'refresh_token',
            'password',
            'pass',
            'pin',
            'authorization',
            'signature',
            'x-signature',
            'private',
            'client_secret',
            'webhook_secret',
        ];
    }

    public function redact(mixed $value, int $maxDepth = 8): mixed
    {
        return $this->walk($value, 0, $maxDepth);
    }

    private function walk(mixed $value, int $depth, int $maxDepth): mixed
    {
        if ($depth > $maxDepth) {
            return '[redacted:depth]';
        }

        if ($value === null || is_bool($value) || is_int($value) || is_float($value)) {
            return $value;
        }

        if (is_string($value)) {
            return $value;
        }

        if (is_array($value)) {
            $out = [];
            foreach ($value as $k => $v) {
                if (is_string($k) && $this->isSensitiveKey($k)) {
                    $out[$k] = $this->mask($v);
                    continue;
                }
                $out[$k] = $this->walk($v, $depth + 1, $maxDepth);
            }
            return $out;
        }

        if ($value instanceof JsonSerializable) {
            return $this->walk($value->jsonSerialize(), $depth + 1, $maxDepth);
        }

        if (is_object($value) && method_exists($value, 'toArray')) {
            /** @var mixed $arr */
            $arr = $value->toArray();
            return $this->walk($arr, $depth + 1, $maxDepth);
        }

        return '[object]';
    }

    private function isSensitiveKey(string $key): bool
    {
        $k = strtolower(trim($key));
        foreach ($this->sensitiveKeys as $s) {
            $s = strtolower($s);
            if ($k === $s || str_contains($k, $s)) {
                return true;
            }
        }
        return false;
    }

    private function mask(mixed $v): string
    {
        if ($v === null) {
            return '[redacted]';
        }

        $s = is_scalar($v) ? (string)$v : '[redacted]';

        $len = strlen($s);
        if ($len <= 4) {
            return '[redacted]';
        }

        return str_repeat('*', max(0, $len - 4)) . substr($s, -4);
    }
}

