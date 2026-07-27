<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\WebhookRequest;
use PayKit\Payload\Responses\WebhookVerifyResult;

interface PaymentGatewayCardAuthorizationWatcherContract
{
    public function verifyCardAuthorizationWebhook(WebhookRequest $request, ?ConfigBag $config = null): WebhookVerifyResult;
}
