<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\UiManifest;

interface PaymentGatewayUiContract
{
    public function uiManifest(): UiManifest;
}

