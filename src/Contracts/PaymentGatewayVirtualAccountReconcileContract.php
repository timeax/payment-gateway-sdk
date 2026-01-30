<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\ReconcileQuery;
use PayKit\Payload\Responses\ReconcileResult;

interface PaymentGatewayVirtualAccountReconcileContract
{
    public function reconcileVirtualAccounts(ReconcileQuery $query, ?ConfigBag $config = null): ReconcileResult;
}


