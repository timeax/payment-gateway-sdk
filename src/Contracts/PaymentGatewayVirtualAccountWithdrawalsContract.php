<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\VirtualAccountWithdrawalRequest;
use PayKit\Payload\Requests\VirtualAccountWithdrawalVerifyRequest;
use PayKit\Payload\Responses\VirtualAccountWithdrawalResult;
use PayKit\Payload\Responses\VirtualAccountWithdrawalStatusResult;

interface PaymentGatewayVirtualAccountWithdrawalsContract
{
    public function initiateWithdrawal(VirtualAccountWithdrawalRequest $request, ?ConfigBag $config = null): VirtualAccountWithdrawalResult;

    public function verifyWithdrawal(VirtualAccountWithdrawalVerifyRequest $request, ?ConfigBag $config = null): ?VirtualAccountWithdrawalStatusResult;
}


