<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;

interface PaymentGatewayVirtualCardsContract
{
    public function isSupported(?ConfigBag $config = null): bool;
}


