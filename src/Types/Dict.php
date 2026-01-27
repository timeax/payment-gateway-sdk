<?php declare(strict_types=1);

namespace PayKit\Types;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * Small helper wrapper for "opaque" provider blobs / flexible maps.
 *
 * @implements ArrayAccess<string,mixed>
 * @implements IteratorAggregate<string,mixed>
 */
final class Dict implements ArrayAccess, IteratorAggregate, Countable, JsonSerializable
{
    /** @param array<string,mixed> $items */
    public function __construct(private array $items = [])
    {
    }

    /** @return array<string,mixed> */
    public function all(): array
    {
        return $this->items;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->items[$key] ?? $default;
    }

    public function getString(string $key, ?string $default = null): ?string
    {
        $v = $this->items[$key] ?? null;
        if ($v === null) return $default;
        if (is_string($v)) return $v;
        if (is_scalar($v)) return (string)$v;
        return $default;
    }

    public function getInt(string $key, ?int $default = null): ?int
    {
        $v = $this->items[$key] ?? null;
        if ($v === null) return $default;
        if (is_int($v)) return $v;
        if (is_numeric($v)) return (int)$v;
        return $default;
    }

    public function getBool(string $key, ?bool $default = null): ?bool
    {
        $v = $this->items[$key] ?? null;
        if ($v === null) return $default;
        if (is_bool($v)) return $v;
        if (is_string($v)) {
            $vv = strtolower(trim($v));
            if (in_array($vv, ['1', 'true', 'yes', 'on'], true)) return true;
            if (in_array($vv, ['0', 'false', 'no', 'off'], true)) return false;
        }
        if (is_int($v)) return $v === 1;
        return $default;
    }

    /** @return array<mixed>|null */
    public function getArray(string $key, ?array $default = null): ?array
    {
        $v = $this->items[$key] ?? null;
        if ($v === null) return $default;
        return is_array($v) ? $v : $default;
    }

    public function set(string $key, mixed $value): self
    {
        $this->items[$key] = $value;
        return $this;
    }

    public function remove(string $key): self
    {
        unset($this->items[$key]);
        return $this;
    }

    // ArrayAccess

    public function offsetExists(mixed $offset): bool
    {
        return is_string($offset) && $this->has($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return is_string($offset) ? $this->get($offset) : null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!is_string($offset)) {
            throw new \InvalidArgumentException('Dict keys must be strings.');
        }
        $this->set($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        if (is_string($offset)) {
            $this->remove($offset);
        }
    }

    // IteratorAggregate / Countable / JsonSerializable

    public function getIterator(): Traversable
    {
        yield from $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function jsonSerialize(): array
    {
        return $this->items;
    }
}

