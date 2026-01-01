<?php declare(strict_types=1);

namespace PayKit\Exceptions;

final class GatewayCapabilityException extends GatewayRuntimeException
{
    public function __construct(
        public readonly string  $driverKey,
        public readonly string  $capability,  // e.g. contract/interface name, or "refunds"
        public readonly ?string $method = null,
        string                  $message = 'Gateway capability not supported.',
    )
    {
        parent::__construct($message, [
            'driverKey' => $driverKey,
            'capability' => $capability,
            'method' => $method,
        ]);
    }

    public static function notSupported(string $driverKey, string $capability, ?string $method = null): self
    {
        $msg = $method
            ? sprintf('Gateway "%s" does not support %s::%s.', $driverKey, $capability, $method)
            : sprintf('Gateway "%s" does not support %s.', $driverKey, $capability);

        return new self($driverKey, $capability, $method, $msg);
    }
}