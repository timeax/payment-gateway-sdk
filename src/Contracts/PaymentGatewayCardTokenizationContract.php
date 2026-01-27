<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\CardTokenizeRequest;
use PayKit\Payload\Responses\CardTokenizeResult;

interface PaymentGatewayCardTokenizationContract
{
    public function tokenizeCard(CardTokenizeRequest $request, ?ConfigBag $config): CardTokenizeResult;
}


