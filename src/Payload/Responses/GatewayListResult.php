<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * @implements IteratorAggregate<int,GatewayListItem>
 */
final readonly class GatewayListResult implements JsonSerializable, IteratorAggregate
{
    /** @param array<int,GatewayListItem> $items */
    public function __construct(public array $items)
    {
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function jsonSerialize(): array
    {
        return [
            'items' => $this->items,
        ];
    }
}

