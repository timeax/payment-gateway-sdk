<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\ListVirtualAccountsRequest;
use PayKit\Payload\Requests\VirtualAccountCreateRequest;
use PayKit\Payload\Requests\VirtualAccountGetRequest;
use PayKit\Payload\Responses\VirtualAccountList;
use PayKit\Payload\Responses\VirtualAccountRecord;

interface PaymentGatewayVirtualAccountsContract
{
    public function createVirtualAccount(VirtualAccountCreateRequest $request, ?ConfigBag $config = null): VirtualAccountRecord;

    public function getVirtualAccount(VirtualAccountGetRequest $request, ?ConfigBag $config = null): ?VirtualAccountRecord;

    public function listVirtualAccounts(ListVirtualAccountsRequest $request, ?ConfigBag $config = null): VirtualAccountList;
}


