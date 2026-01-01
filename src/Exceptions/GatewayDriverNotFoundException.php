<?php declare(strict_types=1);

namespace PayKit\Exceptions;

final class GatewayDriverNotFoundException extends GatewayRuntimeException
{
    /** @param array<int,string> $available */
    public function __construct(
        public readonly string $driverKey,
        public readonly array  $available = [],
    )
    {
        parent::__construct(
            $available
                ? sprintf('Gateway driver not found for key "%s". Available: %s', $driverKey, implode(', ', $available))
                : sprintf('Gateway driver not found for key "%s".', $driverKey),
            ['driverKey' => $driverKey, 'available' => $available],
        );
    }

    /** @param array<int,string> $available */
    public static function for(string $driverKey, array $available = []): self
    {
        return new self($driverKey, $available);
    }
}