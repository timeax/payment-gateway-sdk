<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Responses\CardRevealSessionResult;

interface PaymentGatewayCardSensitiveDetailsContract
{
    public function createCardRevealSession(string $cardId, ?ConfigBag $config = null): CardRevealSessionResult;
}
