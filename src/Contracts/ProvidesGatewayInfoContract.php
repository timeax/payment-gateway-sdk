<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Requests\GatewayListFilter;

interface ProvidesGatewayInfoContract
{
    /**
     * Host-defined â€œinfoâ€ blob for UI (DTOs, labels, tags, etc).
     * Must be JSON-serializable.
     *
     * @return array<string, mixed>
     */
    public function getInfo(?GatewayListFilter $filter = null): array;
}

