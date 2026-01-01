<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final class ValidationResult implements JsonSerializable
{
    /** @var array<string, array<int, ConfigValidationError>> */
    private array $errors = [];

    public function __construct(public readonly bool $ok = true)
    {
    }

    public static function ok(): self
    {
        return new self(true);
    }

    public static function fail(): self
    {
        return new self(false);
    }

    public function addError(string $field, string $message, ?string $code = null): self
    {
        $this->errors[$field] ??= [];
        $this->errors[$field][] = new ConfigValidationError($field, $message, $code);
        return $this;
    }

    public function isOk(): bool
    {
        return $this->ok && $this->errors === [];
    }

    /** @return array<string, array<int, ConfigValidationError>> */
    public function errors(): array
    {
        return $this->errors;
    }

    public function jsonSerialize(): array
    {
        return [
            'ok' => $this->isOk(),
            'errors' => array_map(
                static fn (array $errs) => array_map(static fn (ConfigValidationError $e) => $e->jsonSerialize(), $errs),
                $this->errors
            ),
        ];
    }
}