<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\CardTokenizeRequest;
use PayKit\Payload\Responses\CardTokenizeResult;

interface PaymentGatewayCardTokenizationContract
{
    public function tokenizeCard(CardTokenizeRequest $request, ?GatewayConfig $config): CardTokenizeResult;
}