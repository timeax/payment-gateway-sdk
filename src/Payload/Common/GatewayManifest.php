<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class GatewayManifest implements JsonSerializable
{
    public function __construct(
        public string               $driverKey,
        public GatewaySupportMatrix $supportMatrix,
        public GatewayFeatureSet    $features,
        public ?UiManifest          $ui = null,
        public ?GatewayRequirements $requirements = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'driverKey' => $this->driverKey,
            'supportMatrix' => $this->supportMatrix->jsonSerialize(),
            'features' => $this->features->jsonSerialize(),
            'ui' => $this->ui?->jsonSerialize(),
            'requirements' => $this->requirements?->jsonSerialize(),
        ];
    }
}