<?php declare(strict_types=1);

namespace PayKit\Tests\Payload;

use PayKit\Payload\Common\Country;
use PayKit\Payload\Common\CustomerIdentity;
use PayKit\Payload\Common\IdentityIdentifier;
use PHPUnit\Framework\TestCase;

final class CustomerIdentityTest extends TestCase
{
    public function test_customer_identity_serialization(): void
    {
        $identity = new CustomerIdentity(
            providerCustomerId: 'cus_998877',
            name: 'Jane Doe',
            email: 'jane@example.com',
            phone: '+2348012345678',
            dateOfBirth: '1990-05-15',
            identifiers: [
                new IdentityIdentifier(type: 'bvn', value: '22113344556', country: new Country('NG')),
                new IdentityIdentifier(type: 'nin', value: '11223344556', country: new Country('NG')),
            ]
        );

        $json = $identity->jsonSerialize();

        $this->assertEquals('cus_998877', $json['providerCustomerId']);
        $this->assertEquals('Jane Doe', $json['name']);
        $this->assertCount(2, $json['identifiers']);
        $this->assertEquals('bvn', $json['identifiers'][0]['type']);
        $this->assertEquals('22113344556', $json['identifiers'][0]['value']);
    }
}
