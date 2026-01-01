<?php declare(strict_types=1);

namespace PayKit\Exceptions;

final class GatewayConfigException extends GatewayRuntimeException
{
    /**
     * @param array<string,mixed> $errors (field => message|messages|structured)
     */
    public function __construct(
        public readonly string $driverKey,
        public readonly array  $errors = [],
        string                 $message = 'Invalid gateway configuration.',
    )
    {
        parent::__construct($message, ['driverKey' => $driverKey, 'errors' => $errors]);
    }

    /**
     * @param array<string,mixed> $errors
     */
    public static function invalid(string $driverKey, array $errors = [], string $message = 'Invalid gateway configuration.'): self
    {
        return new self($driverKey, $errors, $message);
    }
}