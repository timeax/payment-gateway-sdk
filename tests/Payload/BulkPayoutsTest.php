<?php declare(strict_types=1);

namespace PayKit\Tests\Payload;

use PayKit\Payload\Common\BankAccountDestination;
use PayKit\Payload\Common\BulkPayoutItem;
use PayKit\Payload\Common\CanonicalPayoutStatus;
use PayKit\Payload\Common\Country;
use PayKit\Payload\Common\Currency;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\PayoutDestination;
use PayKit\Payload\Common\PayoutSource;
use PayKit\Payload\Common\PayoutSourceType;
use PayKit\Payload\Common\Reference;
use PayKit\Payload\Requests\BulkPayoutRequest;
use PayKit\Payload\Responses\BulkPayoutResult;
use PayKit\Payload\Responses\PayoutResult;
use PHPUnit\Framework\TestCase;

final class BulkPayoutsTest extends TestCase
{
    public function test_bulk_payout_request_serialization(): void
    {
        $dest1 = new PayoutDestination(
            payload: new BankAccountDestination(accountNumber: '0123456789', bankCode: '058')
        );

        $item1 = new BulkPayoutItem(
            itemId: 'item_1',
            money: Money::from(10000, 'NGN'),
            destination: $dest1->payload
        );

        $request = new BulkPayoutRequest(
            reference: new Reference('batch_ref_100'),
            items: [$item1],
            source: new PayoutSource(PayoutSourceType::provider_balance),
            title: 'Monthly Salary Batch'
        );

        $json = $request->jsonSerialize();

        $this->assertEquals('batch_ref_100', $json['reference']);
        $this->assertEquals('Monthly Salary Batch', $json['title']);
        $this->assertCount(1, $json['items']);
        $this->assertEquals('item_1', $json['items'][0]['itemId']);
    }

    public function test_bulk_payout_result_serialization(): void
    {
        $itemResult = new PayoutResult(
            reference: new Reference('item_ref_1'),
            providerRef: null,
            status: CanonicalPayoutStatus::succeeded
        );

        $result = new BulkPayoutResult(
            reference: new Reference('batch_ref_100'),
            providerRef: null,
            status: CanonicalPayoutStatus::succeeded,
            totalCount: 1,
            successCount: 1,
            failedCount: 0,
            itemResults: [$itemResult]
        );

        $json = $result->jsonSerialize();

        $this->assertEquals('batch_ref_100', $json['reference']);
        $this->assertEquals(1, $json['totalCount']);
        $this->assertEquals(1, $json['successCount']);
        $this->assertEquals('succeeded', $json['itemResults'][0]['status']);
    }
}
