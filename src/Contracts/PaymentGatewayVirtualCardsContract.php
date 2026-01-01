<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;

interface PaymentGatewayVirtualCardsContract
{
    public function isSupported(?GatewayConfig $config = null): bool;
}