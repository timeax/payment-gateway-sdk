<?php declare(strict_types=1);

namespace PayKit\Support;

use InvalidArgumentException;

final class Assert
{
    public static function true(bool $value, string $message = 'Assertion failed.'): void
    {
        if (!$value) {
            throw new InvalidArgumentException($message);
        }
    }

    public static function notEmptyString(?string $value, string $message = 'Expected a non-empty string.'): string
    {
        if ($value === null || trim($value) === '') {
            throw new InvalidArgumentException($message);
        }

        return $value;
    }

    /**
     * @template T
     * @param T|null $value
     * @return T
     */
    public static function notNull(mixed $value, string $message = 'Expected a non-null value.'): mixed
    {
        if ($value === null) {
            throw new InvalidArgumentException($message);
        }

        return $value;
    }

    /**
     * @param array<string,mixed> $array
     */
    public static function hasKey(array $array, string $key, string $message = 'Missing required key.'): void
    {
        if (!array_key_exists($key, $array)) {
            throw new InvalidArgumentException($message . ' Key: ' . $key);
        }
    }

    /**
     * @param array<int|string, mixed> $allowed
     */
    public static function oneOf(mixed $value, array $allowed, string $message = 'Unexpected value.'): void
    {
        if (!in_array($value, $allowed, true)) {
            throw new InvalidArgumentException($message);
        }
    }
}

