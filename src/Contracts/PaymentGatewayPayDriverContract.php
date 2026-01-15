<?php declare(strict_types=1);

namespace PayKit\Contracts;

interface PaymentGatewayPayDriverContract
    extends PaymentGatewayDriverContract,
    PaymentGatewayPaymentsContract,
    PaymentGatewayVerificationContract,
    PaymentGatewayWebhooksContract,
    PaymentGatewayPaymentStatusMapperContract
{
}