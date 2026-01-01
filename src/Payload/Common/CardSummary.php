<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class CardSummary implements JsonSerializable
{
    public function __construct(
        public CardBrand        $brand,
        public string           $last4,
        public ?int             $expMonth = null,
        public ?int             $expYear = null,
        public ?CardFingerprint $fingerprint = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'brand' => $this->brand->value,
            'last4' => $this->last4,
            'expMonth' => $this->expMonth,
            'expYear' => $this->expYear,
            'fingerprint' => $this->fingerprint?->value,
        ];
    }
}