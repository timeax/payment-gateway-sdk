<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\PaymentVerifyRequest;
use PayKit\Payload\Responses\PaymentVerifyResult;

interface PaymentGatewayVerificationContract
{
    public function verifyPayment(PaymentVerifyRequest $request, ?ConfigBag $config = null): PaymentVerifyResult;
}


