<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Requests\GatewayListFilter;

/**
 * If a registered gateway has a provider instance/class, it can optionally
 * decide whether it should be shown for a given filter context.
 *
 * This runs FIRST in Pay::list() filtering.
 */
interface EvaluatesGatewayVisibilityContract
{
    public function shouldShow(GatewayListFilter $filter): bool;
}