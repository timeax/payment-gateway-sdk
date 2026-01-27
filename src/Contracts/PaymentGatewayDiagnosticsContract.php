<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\HealthCheckResult;

interface PaymentGatewayDiagnosticsContract
{
    public function diagnostics(?ConfigBag $config): HealthCheckResult;
}


