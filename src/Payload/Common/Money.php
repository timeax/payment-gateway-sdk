<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class Money implements JsonSerializable
{
    public function __construct(
        public Amount   $amount,
        public Currency $currency,
    )
    {
    }

    public static function from(int|string $amount, string $currency): self
    {
        return new self(Amount::from($amount), Currency::from($currency));
    }

    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->amount->toString(),
            'currency' => $this->currency->toString(),
        ];
    }

    /** @return array{amount:string,currency:string} */
    public function toArray(): array
    {
        /** @var array{amount:string,currency:string} $arr */
        $arr = $this->jsonSerialize();
        return $arr;
    }
}