<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Common\GatewayConfigSchema;
use PayKit\Payload\Common\HealthCheckResult;
use PayKit\Payload\Common\ValidationResult;

interface PaymentGatewayDriverContract extends PaymentGatewayWebhooksContract
{
    public function driverKey(): string;

    public function configSchema(): GatewayConfigSchema;

    public function validateConfig(?GatewayConfig $config = null): ValidationResult;

    /** Safe keys only (never secrets). */
    public function publicConfig(?GatewayConfig $config = null): array;

    public function healthCheck(?GatewayConfig $config = null): ?HealthCheckResult;

    public function redactForLogs(mixed $payload): mixed;
}