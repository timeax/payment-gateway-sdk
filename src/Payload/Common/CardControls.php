<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class CardControls implements JsonSerializable
{
    /**
     * @param array<string> $allowedMccs
     * @param array<string> $blockedMccs
     * @param array<string> $allowedCountries
     * @param array<string> $blockedCountries
     */
    public function __construct(
        public ?Money $perTransactionLimit = null,
        public ?Money $dailyLimit = null,
        public ?Money $monthlyLimit = null,
        public array $allowedMccs = [],
        public array $blockedMccs = [],
        public array $allowedCountries = [],
        public array $blockedCountries = [],
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'perTransactionLimit' => $this->perTransactionLimit?->toArray(),
            'dailyLimit' => $this->dailyLimit?->toArray(),
            'monthlyLimit' => $this->monthlyLimit?->toArray(),
            'allowedMccs' => $this->allowedMccs,
            'blockedMccs' => $this->blockedMccs,
            'allowedCountries' => $this->allowedCountries,
            'blockedCountries' => $this->blockedCountries,
        ];
    }
}
