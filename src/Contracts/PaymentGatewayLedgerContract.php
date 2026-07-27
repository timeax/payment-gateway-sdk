<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\AccountTransaction;
use PayKit\Payload\Requests\LedgerQuery;
use PayKit\Payload\Responses\LedgerPage;

interface PaymentGatewayLedgerContract
{
    public function getLedger(LedgerQuery $query, ?ConfigBag $config = null): LedgerPage;

    public function getTransaction(string $transactionId, ?ConfigBag $config = null): ?AccountTransaction;
}
