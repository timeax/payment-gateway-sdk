<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;
use PayKit\Payload\Common\Country;
use PayKit\Payload\Common\Currency;

final readonly class GatewayListFilter implements JsonSerializable
{
    public function __construct(
        public ?Currency             $currency = null,
        public ?Country              $country = null,

        /**
         * If your host wants to filter sandbox-only or live-only.
         * null = don’t care.
         */
        public ?bool                 $sandbox = null,

        public ?GatewayFeatureFilter $features = null,

        /**
         * Extra host-defined inputs (user prefs, account tier, etc).
         * Provider may use this inside shouldShow()/getInfo().
         *
         * @var array<string,mixed>
         */
        public array                 $context = [],
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'currency' => $this->currency,
            'country' => $this->country,
            'sandbox' => $this->sandbox,
            'features' => $this->features,
            'context' => (object)$this->context,
        ];
    }
}