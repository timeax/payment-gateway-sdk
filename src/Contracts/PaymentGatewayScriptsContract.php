<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Common\GatewayScript;

interface PaymentGatewayScriptsContract
{
    /** @return array<int,GatewayScript> */
    public function getScripts(?GatewayConfig $config = null): array;
}