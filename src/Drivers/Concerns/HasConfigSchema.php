<?php declare(strict_types=1);

namespace PayKit\Drivers\Concerns;

use Timeax\ConfigSchema\Schema\ConfigField;
use Timeax\ConfigSchema\Schema\ConfigSchema;
use Timeax\ConfigSchema\Support\ConfigBag;
use Timeax\ConfigSchema\Support\ConfigValidationResult;

trait HasConfigSchema
{
    /** @return array<int,ConfigField> */
    protected function configFields(): array
    {
        return [];
    }

    public function configSchema(): ConfigSchema
    {
        return new ConfigSchema($this->configFields());
    }

    public function validateConfig(?ConfigBag $config = null): ConfigValidationResult
    {
        // hybrid: allow per-call override, otherwise fall back to constructor default
        $cfg = method_exists($this, 'resolveConfig')
            ? $this->resolveConfig($config)
            : ($config ?? null);

        if (!$cfg) {
            // If resolveConfig() exists it would have thrown already; this is a last-resort fallback.
            return ConfigValidationResult::fail()
                ->addError('config', 'Gateway configuration missing.');
        }

        $schema = $this->configSchema();

        if (method_exists($schema, 'validate')) {
            /** @var ConfigValidationResult $res */
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

            $val = $field->secret
                ? $cfg->secret($name)
                : $cfg->option($name);

            if ($val === null && !$field->secret) {
                $val = $cfg->secret($name);
            }

            if ($val === null || (is_string($val) && trim($val) === '')) {
                $errors[$name][] = 'Required';
            }
        }


        return $errors
            ? ConfigValidationResult::fail()->addErrors($errors)
            : ConfigValidationResult::ok();
    }
}


