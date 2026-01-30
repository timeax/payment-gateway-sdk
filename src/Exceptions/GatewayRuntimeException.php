<?php declare(strict_types=1);

namespace PayKit\Exceptions;

use RuntimeException;
use Throwable;

class GatewayRuntimeException extends RuntimeException
{
    /** @param array<string,mixed> $context */
    public function __construct(
        string $message = 'Gateway runtime error.',
        public readonly array $context = [],
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    /** @param array<string,mixed> $context */
    public static function withContext(string $message, array $context = [], int $code = 0, ?Throwable $previous = null): static
    {
        return new static($message, $context, $code, $previous);
    }
}

