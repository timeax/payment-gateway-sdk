<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Events\VirtualAccountEventBatch;
use PayKit\Payload\Requests\PollVirtualAccountEventsQuery;
use PayKit\Payload\Responses\PollSpec;

interface PaymentGatewayVirtualAccountPollingWatcherContract
{
    public function pollSpec(?GatewayConfig $config = null): PollSpec;

    public function pollVirtualAccountEvents(PollVirtualAccountEventsQuery $query, ?GatewayConfig $config = null): VirtualAccountEventBatch;
}