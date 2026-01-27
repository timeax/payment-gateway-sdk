<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;

final readonly class PollSpec implements JsonSerializable
{
    public function __construct(
        public int $intervalSeconds = 60,
        public int $maxBatchSize = 100,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'intervalSeconds' => $this->intervalSeconds,
            'maxBatchSize' => $this->maxBatchSize,
        ];
    }
}

