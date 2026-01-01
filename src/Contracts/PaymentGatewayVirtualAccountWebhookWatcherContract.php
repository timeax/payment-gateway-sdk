<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Events\VirtualAccountEvent;
use PayKit\Payload\Requests\WebhookRequest;
use PayKit\Payload\Responses\WebhookVerifyResult;

interface PaymentGatewayVirtualAccountWebhookWatcherContract
{
    public function verifyWebhook(WebhookRequest $request, ?GatewayConfig $config = null): WebhookVerifyResult;

    public function parseVirtualAccountEvent(WebhookRequest $request, ?GatewayConfig $config = null): VirtualAccountEvent;
}