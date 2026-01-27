<?php declare(strict_types=1);

namespace PayKit\Drivers;

use JsonSerializable;
use PayKit\Contracts\PaymentGatewayDriverContract;
use PayKit\Exceptions\GatewayCapabilityException;
use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\HealthCheckResult;
use PayKit\Payload\Events\WebhookHandleResult;
use PayKit\Payload\Requests\WebhookRequest;

abstract class AbstractPaymentGatewayDriver implements PaymentGatewayDriverContract
{
    use Concerns\ResolvesConfig;
    use Concerns\HasConfigSchema;
    use Concerns\RedactsSecrets;
    use Concerns\MapsStatuses;
    use Concerns\BuildsManifest;

    public function __construct(?ConfigBag $config = null)
    {
        $this->setDefaultConfig($config);
    }

    /**
     * Drivers MUST implement health checks (hybrid: optional override config).
     */
    abstract public function healthCheck(?ConfigBag $config = null): ?HealthCheckResult;

    public function publicConfig(?ConfigBag $config = null): array
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

    final public function handleWebhook(WebhookRequest $request, ?ConfigBag $config = null): WebhookHandleResult
    {
        $verified = $this->verifyWebhook($request, $config);

        if (!$verified->ok) {
            return WebhookHandleResult::rejected($verified);
        }

        $event = $this->parseWebhook($request, $verified, $config);

        return WebhookHandleResult::accepted($verified, $event);
    }

    protected function requireCapability(string $contractOrName, ?string $method = null): never
    {
        throw GatewayCapabilityException::notSupported($this->driverKey(), $contractOrName, $method);
    }
}


