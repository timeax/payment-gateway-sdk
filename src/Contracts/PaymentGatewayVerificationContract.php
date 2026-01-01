<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\PaymentVerifyRequest;
use PayKit\Payload\Responses\PaymentVerifyResult;

interface PaymentGatewayVerificationContract
{
    public function verifyPayment(PaymentVerifyRequest $request, ?GatewayConfig $config = null): PaymentVerifyResult;
}