<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\VirtualCardRecord;
use PayKit\Payload\Requests\VirtualCardStatusUpdateRequest;

interface PaymentGatewayCardManagementContract
{
    public function updateCardStatus(VirtualCardStatusUpdateRequest $request, ?ConfigBag $config = null): VirtualCardRecord;

    public function freezeCard(string $cardId, ?ConfigBag $config = null): VirtualCardRecord;

    public function unfreezeCard(string $cardId, ?ConfigBag $config = null): VirtualCardRecord;

    public function terminateCard(string $cardId, ?ConfigBag $config = null): VirtualCardRecord;
}
