<?php declare(strict_types=1);

namespace PayKit\Drivers\Concerns;

use PayKit\Payload\Common\GatewayFeatureSet;
use PayKit\Payload\Common\GatewayManifest;
use PayKit\Payload\Common\GatewayRequirements;
use PayKit\Payload\Common\GatewaySupportMatrix;
use PayKit\Payload\Common\UiManifest;

trait BuildsManifest
{
    protected function buildManifest(
        GatewaySupportMatrix $support,
        GatewayFeatureSet    $features,
        ?UiManifest          $ui = null,
        ?GatewayRequirements $requirements = null,
    ): GatewayManifest
    {
        return new GatewayManifest(
            driverKey: $this->driverKey(),
            supportMatrix: $support,
            features: $features,
            ui: $ui,
            requirements: $requirements,
        );
    }
}