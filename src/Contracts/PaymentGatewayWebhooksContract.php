<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Events\WebhookEvent;
use PayKit\Payload\Events\WebhookHandleResult;
use PayKit\Payload\Requests\WebhookRequest;
use PayKit\Payload\Responses\WebhookVerifyResult;

interface PaymentGatewayWebhooksContract
{
    public function verifyWebhook(WebhookRequest $request, ?ConfigBag $config = null): WebhookVerifyResult;

    public function parseWebhook(WebhookRequest $request, WebhookVerifyResult $verified, ?ConfigBag $config = null): WebhookEvent;

    public function handleWebhook(WebhookRequest $request, ?ConfigBag $config = null): WebhookHandleResult;
}


