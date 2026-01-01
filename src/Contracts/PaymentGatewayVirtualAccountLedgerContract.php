<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\VirtualAccountLedgerEntryQuery;
use PayKit\Payload\Requests\VirtualAccountLedgerQuery;
use PayKit\Payload\Responses\VirtualAccountLedgerEntry;
use PayKit\Payload\Responses\VirtualAccountLedgerPage;

interface PaymentGatewayVirtualAccountLedgerContract
{
    public function getLedger(VirtualAccountLedgerQuery $query, ?GatewayConfig $config = null): VirtualAccountLedgerPage;

    public function getLedgerEntry(VirtualAccountLedgerEntryQuery $query, ?GatewayConfig $config = null): ?VirtualAccountLedgerEntry;
}