<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Events\WebhookEvent;
use PayKit\Payload\Requests\WebhookRequest;
use PayKit\Payload\Responses\WebhookVerifyResult;

interface PaymentGatewayWebhooksContract
{
    public function verifyWebhook(WebhookRequest $request, ?GatewayConfig $config = null): WebhookVerifyResult;

    public function parseWebhook(WebhookRequest $request, ?GatewayConfig $config = null): WebhookEvent;
}