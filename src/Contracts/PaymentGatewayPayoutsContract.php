<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\PayoutRequest;
use PayKit\Payload\Requests\PayoutVerifyRequest;
use PayKit\Payload\Responses\PayoutResult;
use PayKit\Payload\Responses\PayoutStatusResult;

interface PaymentGatewayPayoutsContract
{
    public function initiatePayout(PayoutRequest $request, ?ConfigBag $config = null): PayoutResult;

    public function verifyPayout(PayoutVerifyRequest $request, ?ConfigBag $config = null): ?PayoutStatusResult;
}


