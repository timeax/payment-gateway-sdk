<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

if (!class_exists(__NAMESPACE__ . '\\ConfigValidationError', false)) {
    class_alias(\Timeax\ConfigSchema\Support\ConfigValidationError::class, __NAMESPACE__ . '\\ConfigValidationError');
}
