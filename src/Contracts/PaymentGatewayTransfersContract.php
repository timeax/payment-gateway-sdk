<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\TransferRequest;
use PayKit\Payload\Responses\TransferResult;

interface PaymentGatewayTransfersContract
{
    public function transfer(TransferRequest $request, ?ConfigBag $config = null): TransferResult;

    public function getTransfer(string $transferId, ?ConfigBag $config = null): ?TransferResult;
}
