<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\CanonicalPaymentStatus;
use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\PaymentInitiateRequest;
use PayKit\Payload\Responses\PaymentInitiateResult;

interface PaymentGatewayPaymentsContract
{
    public function initiatePayment(PaymentInitiateRequest $request, ?GatewayConfig $config = null): PaymentInitiateResult;

    /** Normalize provider payload to canonical status. */
    public function mapStatus(mixed $rawPayload): CanonicalPaymentStatus;
}