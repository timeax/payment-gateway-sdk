<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\DisputeQuery;
use PayKit\Payload\Responses\DisputeSnapshot;

interface PaymentGatewayDisputesContract
{
    public function getDispute(DisputeQuery $query, ?GatewayConfig $config): ?DisputeSnapshot;
}