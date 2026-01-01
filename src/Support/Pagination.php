<?php declare(strict_types=1);

namespace PayKit\Support;

final class Pagination
{
    public static function limit(?int $limit, int $default = 50, int $min = 1, int $max = 200): int
    {
        $v = $limit ?? $default;
        if ($v < $min) {
            return $min;
        }
        if ($v > $max) {
            return $max;
        }
        return $v;
    }

    /**
     * @template T
     * @param array<int,T> $items
     * @return array{items:array<int,T>,cursor?:string}
     */
    public static function page(array $items, ?string $cursor = null): array
    {
        return ['items' => $items, 'cursor' => $cursor];
    }
}