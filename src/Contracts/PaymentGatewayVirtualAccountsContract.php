<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\ListVirtualAccountsRequest;
use PayKit\Payload\Requests\VirtualAccountCreateRequest;
use PayKit\Payload\Requests\VirtualAccountGetRequest;
use PayKit\Payload\Responses\VirtualAccountList;
use PayKit\Payload\Responses\VirtualAccountRecord;

interface PaymentGatewayVirtualAccountsContract
{
    public function createVirtualAccount(VirtualAccountCreateRequest $request, ?GatewayConfig $config = null): VirtualAccountRecord;

    public function getVirtualAccount(VirtualAccountGetRequest $request, ?GatewayConfig $config = null): ?VirtualAccountRecord;

    public function listVirtualAccounts(ListVirtualAccountsRequest $request, ?GatewayConfig $config = null): VirtualAccountList;
}