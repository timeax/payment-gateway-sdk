<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\ReconcileQuery;
use PayKit\Payload\Responses\ReconcileResult;

interface PaymentGatewayReconcileContract
{
    public function reconcile(ReconcileQuery $query, ?GatewayConfig $config = null): ReconcileResult;
}