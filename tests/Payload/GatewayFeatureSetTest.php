<?php declare(strict_types=1);

namespace PayKit\Tests\Payload;

use PayKit\Payload\Common\GatewayFeatureSet;
use PHPUnit\Framework\TestCase;

final class GatewayFeatureSetTest extends TestCase
{
    public function test_v2_feature_set_serialization(): void
    {
        $features = new GatewayFeatureSet(
            payments: true,
            refunds: true,
            payouts: true,
            payoutDestinationResolver: true,
            balanceAccounts: true,
            ledger: true,
            internalTransfers: true,
            cardsIssuing: true,
            cardsManagement: true,
            cardsControls: true,
            cardsAuthWatcher: true,
            cardsSensitiveReveal: true,
        );

        $json = $features->jsonSerialize();

        $this->assertTrue($json['payments']);
        $this->assertTrue($json['refunds']);
        $this->assertTrue($json['payouts']);
        $this->assertTrue($json['payoutDestinationResolver']);
        $this->assertTrue($json['balanceAccounts']);
        $this->assertTrue($json['ledger']);
        $this->assertTrue($json['internalTransfers']);
        $this->assertTrue($json['cardsIssuing']);
        $this->assertTrue($json['cardsManagement']);
        $this->assertTrue($json['cardsControls']);
        $this->assertTrue($json['cardsAuthWatcher']);
        $this->assertTrue($json['cardsSensitiveReveal']);
    }
}
