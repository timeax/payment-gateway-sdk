<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\InboundTransferDecisionRequest;
use PayKit\Payload\Responses\InboundTransferDecisionResult;

interface PaymentGatewayInboundTransferApprovalContract
{
    public function approveInboundTransfer(InboundTransferDecisionRequest $request, ?ConfigBag $config = null): InboundTransferDecisionResult;

    public function declineInboundTransfer(InboundTransferDecisionRequest $request, ?ConfigBag $config = null): InboundTransferDecisionResult;
}
