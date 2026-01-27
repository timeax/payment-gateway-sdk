<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Country;
use PayKit\Payload\Common\Currency;

final readonly class GatewayListFilter implements JsonSerializable
{
    /**
     * @param array<int,Currency> $currencies
     * @param array<string,mixed> $context
     */
    public function __construct(
        public array                 $currencies = [], // empty = donâ€™t care
        public ?Country              $country = null,

        /**
         * If your host wants to filter sandbox-only or live-only.
         * null = donâ€™t care.
         */
        public ?bool                 $sandbox = null,

        public ?GatewayFeatureFilter $features = null,

        /**
         * Extra host-defined inputs (user prefs, account tier, etc).
         * Provider may use this inside shouldShow()/getInfo().
         */
        public array                 $context = [],
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'currencies' => array_map(
                static fn(Currency $c) => $c->toString(),
                $this->currencies
            ),
            'country' => $this->country?->toString(),
            'sandbox' => $this->sandbox,
            'features' => $this->features?->jsonSerialize(),
            'context' => (object)$this->context,
        ];
    }
}

