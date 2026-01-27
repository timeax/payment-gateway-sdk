<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

if (!class_exists(__NAMESPACE__ . '\\ConfigField', false)) {
    class_alias(\Timeax\ConfigSchema\Schema\ConfigField::class, __NAMESPACE__ . '\\ConfigField');
}
