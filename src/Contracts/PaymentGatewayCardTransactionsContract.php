<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\LedgerQuery;
use PayKit\Payload\Responses\LedgerPage;

interface PaymentGatewayCardTransactionsContract
{
    public function listCardTransactions(string $cardId, LedgerQuery $query, ?ConfigBag $config = null): LedgerPage;
}
