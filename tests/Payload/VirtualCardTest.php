<?php declare(strict_types=1);

namespace PayKit\Tests\Payload;

use PayKit\Payload\Common\CardBrand;
use PayKit\Payload\Common\CardControls;
use PayKit\Payload\Common\Currency;
use PayKit\Payload\Common\Money;
use PayKit\Payload\Common\VirtualCardRecord;
use PayKit\Payload\Common\VirtualCardStatus;
use PHPUnit\Framework\TestCase;

final class VirtualCardTest extends TestCase
{
    public function test_virtual_card_record_serialization(): void
    {
        $controls = new CardControls(
            perTransactionLimit: Money::from(50000, 'USD'),
            dailyLimit: Money::from(200000, 'USD'),
            allowedMccs: ['5732', '5812']
        );

        $card = new VirtualCardRecord(
            id: 'card_12345',
            status: VirtualCardStatus::active,
            cardholderName: 'Alice Smith',
            last4: '4321',
            brand: CardBrand::visa,
            currency: new Currency('USD'),
            balanceAccountId: 'bal_9999',
            expiresAt: '12/28',
            controls: $controls
        );

        $json = $card->jsonSerialize();

        $this->assertEquals('card_12345', $json['id']);
        $this->assertEquals('active', $json['status']);
        $this->assertEquals('Alice Smith', $json['cardholderName']);
        $this->assertEquals('visa', $json['brand']);
        $this->assertEquals('50000', $json['controls']['perTransactionLimit']['amount']);
        $this->assertEquals(['5732', '5812'], $json['controls']['allowedMccs']);
    }
}
