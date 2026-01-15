<?php declare(strict_types=1);

namespace PayKit\Drivers;

use JsonSerializable;
use PayKit\Contracts\PaymentGatewayDriverContract;
use PayKit\Exceptions\GatewayCapabilityException;
use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Common\HealthCheckResult;

abstract class AbstractPaymentGatewayDriver implements PaymentGatewayDriverContract
{
    use Concerns\ResolvesConfig;
    use Concerns\HasConfigSchema;
    use Concerns\RedactsSecrets;
    use Concerns\MapsStatuses;
    use Concerns\BuildsManifest;

    public function __construct(?GatewayConfig $config = null)
    {
        $this->setDefaultConfig($config);
    }

    /**
     * Drivers MUST implement health checks (hybrid: optional override config).
     */
    abstract public function healthCheck(?GatewayConfig $config = null): ?HealthCheckResult;

    public function publicConfig(?GatewayConfig $config = null): array
    {
        $cfg = $this->resolveConfig($config);

        if (method_exists($cfg, 'public')) {
            /** @var array<string,mixed> $out */
            $out = $cfg->public();
            return $out;
        }

        if (method_exists($cfg, 'toArray')) {
            /** @var array<string,mixed> $data */
            $data = $cfg->toArray();
        } else {
            /** @var array<string,mixed> $data */
            $data = $cfg->jsonSerialize();
        }

        unset(
            $data['secrets'],
            $data['secret'],
            $data['password'],
            $data['pass'],
            $data['pin'],
            $data['client_secret'],
            $data['webhook_secret'],
            $data['private_key'],
            $data['access_token'],
            $data['refresh_token'],
        );

        return $data;
    }

    protected function requireCapability(string $contractOrName, ?string $method = null): never
    {
        throw GatewayCapabilityException::notSupported($this->driverKey(), $contractOrName, $method);
    }
}