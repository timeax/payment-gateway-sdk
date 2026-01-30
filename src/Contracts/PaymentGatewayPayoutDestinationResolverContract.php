<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\PayoutDestinationSnapshot;
use PayKit\Payload\Requests\PayoutDestinationResolveRequest;

interface PaymentGatewayPayoutDestinationResolverContract
{
    public function resolvePayoutDestination(
        PayoutDestinationResolveRequest $request,
        ?ConfigBag $config = null
    ): PayoutDestinationSnapshot;
}


