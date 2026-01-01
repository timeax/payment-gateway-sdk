<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\FrontendConfigRequest;
use PayKit\Payload\Responses\FrontendConfigResult;

interface PaymentGatewayFrontendConfigContract
{
    public function getFrontendConfig(FrontendConfigRequest $request, ?GatewayConfig $config = null): FrontendConfigResult;
}