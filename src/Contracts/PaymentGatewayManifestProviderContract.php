<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Common\GatewayManifest;

interface PaymentGatewayManifestProviderContract
{
    public function getManifest(?GatewayConfig $config = null): GatewayManifest;
}