<?php declare(strict_types=1);

namespace PayKit\Tests\Payload;

use PayKit\Payload\Common\BankAccountDestination;
use PayKit\Payload\Common\Country;
use PayKit\Payload\Common\Currency;
use PayKit\Payload\Common\MobileMoneyDestination;
use PayKit\Payload\Common\PayoutDestination;
use PayKit\Payload\Common\PayoutMethod;
use PHPUnit\Framework\TestCase;

final class PayoutDestinationTest extends TestCase
{
    public function test_bank_account_destination_serialization(): void
    {
        $bankPayload = new BankAccountDestination(
            accountNumber: '0123456789',
            accountName: 'John Doe',
            bankCode: '058',
            routingNumber: '123456',
            iban: 'GB82WEST12345698765432',
            currency: new Currency('NGN'),
            country: new Country('NG'),
            providerData: ['nibss_code' => '000013']
        );

        $destination = new PayoutDestination(
            payload: $bankPayload,
            currency: new Currency('NGN'),
            country: new Country('NG')
        );

        $this->assertEquals(PayoutMethod::bank, $destination->method());

        $serialized = $destination->jsonSerialize();
        $this->assertEquals('bank', $serialized['method']);
        $this->assertEquals('0123456789', $serialized['payload']['accountNumber']);
        $this->assertEquals('000013', $serialized['payload']['providerData']['nibss_code']);
    }

    public function test_mobile_money_destination_serialization(): void
    {
        $momoPayload = new MobileMoneyDestination(
            phoneNumber: '+233241234567',
            operator: 'mtn',
            accountName: 'Kofi Mensah',
            currency: new Currency('GHS'),
            country: new Country('GH')
        );

        $destination = new PayoutDestination(
            payload: $momoPayload,
            currency: new Currency('GHS'),
            country: new Country('GH')
        );

        $this->assertEquals(PayoutMethod::mobile_money, $destination->method());
        $serialized = $destination->jsonSerialize();
        $this->assertEquals('mobile_money', $serialized['method']);
        $this->assertEquals('mtn', $serialized['payload']['operator']);
    }
}
