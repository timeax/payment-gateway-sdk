<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

if (!class_exists(__NAMESPACE__ . '\\ConfigFieldOption', false)) {
    class_alias(\Timeax\ConfigSchema\Schema\ConfigOption::class, __NAMESPACE__ . '\\ConfigFieldOption');
}
