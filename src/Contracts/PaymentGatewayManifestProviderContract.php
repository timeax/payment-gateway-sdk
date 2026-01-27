<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\GatewayManifest;

interface PaymentGatewayManifestProviderContract
{
    public function getManifest(?ConfigBag $config = null): GatewayManifest;
}


