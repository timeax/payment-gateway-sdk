<?php declare(strict_types=1);

namespace PayKit\Payload\Events;

use JsonSerializable;

final readonly class VirtualAccountEventBatch implements JsonSerializable
{
    /**
     * @param array<int,VirtualAccountEvent> $events
     */
    public function __construct(
        public array   $events = [],
        public ?string $cursor = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'events' => array_map(static fn (VirtualAccountEvent $e) => $e->jsonSerialize(), $this->events),
            'cursor' => $this->cursor,
        ];
    }
}

