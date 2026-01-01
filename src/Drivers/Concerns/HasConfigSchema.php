<?php declare(strict_types=1);

namespace PayKit\Drivers\Concerns;

use PayKit\Payload\Common\ConfigField;
use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Common\GatewayConfigSchema;
use PayKit\Payload\Common\ValidationResult;

trait HasConfigSchema
{
    /** @return array<int,ConfigField> */
    protected function configFields(): array
    {
        return [];
    }

    public function configSchema(): GatewayConfigSchema
    {
        return new GatewayConfigSchema($this->configFields());
    }

    public function validateConfig(?GatewayConfig $config = null): ValidationResult
    {
        // hybrid: allow per-call override, otherwise fall back to constructor default
        $cfg = method_exists($this, 'resolveConfig')
            ? $this->resolveConfig($config)
            : ($config ?? null);

        if (!$cfg) {
            // If resolveConfig() exists it would have thrown already; this is a last-resort fallback.
            return new ValidationResult(false, ['config' => 'Gateway configuration missing.']);
        }

        $schema = $this->configSchema();

        if (method_exists($schema, 'validate')) {
            /** @var ValidationResult $res */
            $res = $schema->validate($cfg);
            return $res;
        }

        $errors = [];

        foreach ($this->configFields() as $field) {
            $required = (bool)($field->required ?? false);
            if (!$required) {
                continue;
            }

            $name = (string)($field->name ?? '');
            if ($name === '') {
                continue;
            }

            $val = null;
            if (method_exists($cfg, 'get')) {
                $val = $cfg->get($name);
            } elseif (method_exists($cfg, 'toArray')) {
                /** @var array<string,mixed> $arr */
                $arr = $cfg->toArray();
                $val = $arr[$name] ?? null;
            }

            if ($val === null || (is_string($val) && trim($val) === '')) {
                $errors[$name] = 'Required';
            }
        }

        if (method_exists(ValidationResult::class, 'ok') && method_exists(ValidationResult::class, 'fail')) {
            return $errors ? ValidationResult::fail($errors) : ValidationResult::ok();
        }

        return new ValidationResult($errors === [], $errors);
    }
}