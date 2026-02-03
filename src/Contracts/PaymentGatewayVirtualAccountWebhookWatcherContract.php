<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Events\VirtualAccountEvent;
use PayKit\Payload\Requests\WebhookRequest;
use PayKit\Payload\Responses\WebhookVerifyResult;

interface PaymentGatewayVirtualAccountWebhookWatcherContract
{
    public function verifyVirtualWebhook(WebhookRequest $request, ?ConfigBag $config = null): WebhookVerifyResult;

    public function parseVirtualAccountEvent(WebhookRequest $request, ?ConfigBag $config = null): VirtualAccountEvent;
}


