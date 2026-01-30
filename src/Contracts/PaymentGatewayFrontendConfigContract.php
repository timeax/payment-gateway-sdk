<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\FrontendConfigRequest;
use PayKit\Payload\Responses\FrontendConfigResult;

interface PaymentGatewayFrontendConfigContract
{
    public function getFrontendConfig(FrontendConfigRequest $request, ?ConfigBag $config = null): FrontendConfigResult;
}


