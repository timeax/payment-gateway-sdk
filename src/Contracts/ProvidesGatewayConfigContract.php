<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;

interface ProvidesGatewayConfigContract
{
    public function gatewayDriverKey(): string;

    public function gatewayConfig(): GatewayConfig;
}