<?php declare(strict_types=1);

namespace PayKit\Manager;

use PayKit\Contracts\PaymentGatewayDriverContract;
use PayKit\Contracts\PaymentGatewayManifestProviderContract;
use PayKit\Exceptions\GatewayCapabilityException;
use PayKit\Exceptions\GatewayConfigException;
use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\GatewayManifest;

final readonly class GatewayManager
{
    public function __construct(private DriverResolver $resolver)
    {
    }

    public function make(string $driverKey, ConfigBag $config, bool $validate = true): PaymentGatewayDriverContract
    {
        $driver = $this->resolver->resolve($driverKey, $config);

        if ($validate) {
            $res = $driver->validateConfig($config);

            $ok = $this->readValidationOk($res);
            if ($ok === false) {
                /** @var array<string,mixed> $errors */
                $errors = $this->readValidationErrors($res);
                throw GatewayConfigException::invalid($driverKey, $errors);
            }
        }

        return $driver;
    }

    public function manifest(string $driverKey, ConfigBag $config, bool $validate = true): GatewayManifest
    {
        $driver = $this->make($driverKey, $config, $validate);

        if (!$driver instanceof PaymentGatewayManifestProviderContract) {
            throw GatewayCapabilityException::notSupported(
                $driverKey,
                PaymentGatewayManifestProviderContract::class,
                'getManifest'
            );
        }

        return $driver->getManifest($config);
    }

    private function readValidationOk(mixed $res): ?bool
    {
        if (!is_object($res)) {
            return null;
        }

        // common patterns
        if (method_exists($res, 'ok')) {
            $v = $res->ok();
            return is_bool($v) ? $v : null;
        }

        if (method_exists($res, 'isOk')) {
            $v = $res->isOk();
            return is_bool($v) ? $v : null;
        }

        if (property_exists($res, 'ok')) {
            $v = $res->ok;
            return is_bool($v) ? $v : null;
        }

        return null;
    }

    /** @return array<string,mixed> */
    private function readValidationErrors(mixed $res): array
    {
        if (!is_object($res)) {
            return [];
        }

        if (method_exists($res, 'errors')) {
            $v = $res->errors();
            return is_array($v) ? $v : [];
        }

        if (method_exists($res, 'getErrors')) {
            $v = $res->getErrors();
            return is_array($v) ? $v : [];
        }

        if (property_exists($res, 'errors')) {
            $v = $res->errors;
            return is_array($v) ? $v : [];
        }

        return [];
    }
}


