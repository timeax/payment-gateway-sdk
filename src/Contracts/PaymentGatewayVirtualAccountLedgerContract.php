<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\VirtualAccountLedgerEntryQuery;
use PayKit\Payload\Requests\VirtualAccountLedgerQuery;
use PayKit\Payload\Responses\VirtualAccountLedgerEntry;
use PayKit\Payload\Responses\VirtualAccountLedgerPage;

interface PaymentGatewayVirtualAccountLedgerContract
{
    public function getLedger(VirtualAccountLedgerQuery $query, ?ConfigBag $config = null): VirtualAccountLedgerPage;

    public function getLedgerEntry(VirtualAccountLedgerEntryQuery $query, ?ConfigBag $config = null): ?VirtualAccountLedgerEntry;
}


