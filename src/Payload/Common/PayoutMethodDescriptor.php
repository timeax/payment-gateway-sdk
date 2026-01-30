<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;
use Timeax\ConfigSchema\Schema\ConfigSchema;

final readonly class PayoutMethodDescriptor implements JsonSerializable
{
    /**
     * @param array<int,GatewayScript> $scripts
     * @param array<string,mixed> $meta
     */
    public function __construct(
        public string               $key,        // e.g. "bank.ng.local", "crypto.usdt.trc20"
        public PayoutMethod         $method,     // enum category (bank/wallet/crypto/...)
        public string               $label,      // e.g. "Bank Transfer (NG)"
        public ?ConfigSchema $inputSchema = null, // host-rendered fields (optional)
        public ?UiModuleDescriptor  $ui = null,          // host-mounted UI module (optional)
        public array                $scripts = [],          // scripts needed for UI (optional)
        public ?Currency            $currency = null,
        public ?Country             $country = null,
        public array                $meta = [],
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key,
            'method' => $this->method->value,
            'label' => $this->label,
            'inputSchema' => $this->inputSchema,
            'ui' => $this->ui,
            'scripts' => $this->scripts,
            'currency' => $this->currency?->toString(),
            'country' => $this->country?->toString(),
            'meta' => $this->meta,
        ];
    }
}

