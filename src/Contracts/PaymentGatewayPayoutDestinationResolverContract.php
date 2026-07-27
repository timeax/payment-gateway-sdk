<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\PayoutDestinationResolveRequest;
use PayKit\Payload\Responses\PayoutDestinationResolveResult;

interface PaymentGatewayPayoutDestinationResolverContract
{
    public function resolvePayoutDestination(
        PayoutDestinationResolveRequest $request,
        ?ConfigBag $config = null,
    ): PayoutDestinationResolveResult;
}
