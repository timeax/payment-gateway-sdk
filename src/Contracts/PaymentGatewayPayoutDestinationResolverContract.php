<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Common\PayoutDestinationSnapshot;
use PayKit\Payload\Requests\PayoutDestinationResolveRequest;

interface PaymentGatewayPayoutDestinationResolverContract
{
    public function resolvePayoutDestination(
        PayoutDestinationResolveRequest $request,
        ?GatewayConfig $config = null
    ): PayoutDestinationSnapshot;
}