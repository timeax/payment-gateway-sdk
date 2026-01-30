<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;
use PayKit\Payload\Common\SavedMethod;

final readonly class SavedMethodList implements JsonSerializable
{
    /** @param array<int,SavedMethod> $items */
    public function __construct(public array $items = [])
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'items' => array_map(static fn (SavedMethod $m) => $m->jsonSerialize(), $this->items),
        ];
    }
}



