<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\BulkPayoutRequest;
use PayKit\Payload\Requests\BulkPayoutVerifyRequest;
use PayKit\Payload\Responses\BulkPayoutResult;

interface PaymentGatewayBulkPayoutsContract
{
    public function initiateBulkPayout(BulkPayoutRequest $request, ?ConfigBag $config = null): BulkPayoutResult;

    public function verifyBulkPayout(BulkPayoutVerifyRequest $request, ?ConfigBag $config = null): BulkPayoutResult;
}
