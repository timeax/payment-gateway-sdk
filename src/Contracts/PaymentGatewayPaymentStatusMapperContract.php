<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\CanonicalPaymentStatus;

interface PaymentGatewayPaymentStatusMapperContract
{
    public function mapStatus(mixed $rawPayload): CanonicalPaymentStatus;
}

