<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\RefundRequest;
use PayKit\Payload\Requests\RefundVerifyRequest;
use PayKit\Payload\Responses\RefundResult;
use PayKit\Payload\Responses\RefundStatusResult;

interface PaymentGatewayRefundsContract
{
    public function refund(RefundRequest $request, ?GatewayConfig $config = null): RefundResult;

    public function verifyRefund(RefundVerifyRequest $request, ?GatewayConfig $config = null): ?RefundStatusResult;
}