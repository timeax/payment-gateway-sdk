<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Events\VirtualAccountEventBatch;
use PayKit\Payload\Requests\PollVirtualAccountEventsQuery;
use PayKit\Payload\Responses\PollSpec;

interface PaymentGatewayVirtualAccountPollingWatcherContract
{
    public function pollSpec(?ConfigBag $config = null): PollSpec;

    public function pollVirtualAccountEvents(PollVirtualAccountEventsQuery $query, ?ConfigBag $config = null): VirtualAccountEventBatch;
}


