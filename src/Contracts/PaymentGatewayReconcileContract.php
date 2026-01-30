<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\ReconcileQuery;
use PayKit\Payload\Responses\ReconcileResult;

interface PaymentGatewayReconcileContract
{
    public function reconcile(ReconcileQuery $query, ?ConfigBag $config = null): ReconcileResult;
}


