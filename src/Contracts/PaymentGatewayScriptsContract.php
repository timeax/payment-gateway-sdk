<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\GatewayScript;

interface PaymentGatewayScriptsContract
{
    /** @return array<int,GatewayScript> */
    public function getScripts(?ConfigBag $config = null): array;
}


