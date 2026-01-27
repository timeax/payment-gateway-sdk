<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\DisputeQuery;
use PayKit\Payload\Responses\DisputeSnapshot;

interface PaymentGatewayDisputesContract
{
    public function getDispute(DisputeQuery $query, ?ConfigBag $config): ?DisputeSnapshot;
}


