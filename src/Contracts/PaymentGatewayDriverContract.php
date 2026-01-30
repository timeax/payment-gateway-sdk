<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\HealthCheckResult;
use Timeax\ConfigSchema\Contracts\ProvidesConfigSchema;

interface PaymentGatewayDriverContract extends ProvidesConfigSchema, PaymentGatewayWebhooksContract
{
    public function driverKey(): string;

    public function healthCheck(?ConfigBag $config = null): ?HealthCheckResult;

}


