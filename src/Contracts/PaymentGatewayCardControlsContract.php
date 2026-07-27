<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\CardControls;
use PayKit\Payload\Requests\VirtualCardControlsRequest;

interface PaymentGatewayCardControlsContract
{
    public function updateCardControls(VirtualCardControlsRequest $request, ?ConfigBag $config = null): CardControls;

    public function getCardControls(string $cardId, ?ConfigBag $config = null): ?CardControls;
}
