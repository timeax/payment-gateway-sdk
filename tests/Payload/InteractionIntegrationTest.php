<?php declare(strict_types=1);

namespace PayKit\Tests\Payload;

use Elqora\Interactions\Enums\Presentation;
use Elqora\Interactions\Interactions\Instructions;
use Elqora\Interactions\Interactions\Redirect;
use PayKit\Payload\Common\CanonicalPaymentStatus;
use PayKit\Payload\Common\CanonicalPayoutStatus;
use PayKit\Payload\Common\ProviderRef;
use PayKit\Payload\Common\Reference;
use PayKit\Payload\Common\VirtualAccountStatus;
use PayKit\Payload\Responses\PaymentInitiateResult;
use PayKit\Payload\Responses\PayoutResult;
use PayKit\Payload\Responses\VirtualAccountProvisionResult;
use PHPUnit\Framework\TestCase;

final class InteractionIntegrationTest extends TestCase
{
    public function test_payment_initiate_result_with_redirect_interaction(): void
    {
        $redirect = new Redirect(
            url: 'https://checkout.stripe.com/pay/cs_test_123',
            target: '_self'
        );

        $result = new PaymentInitiateResult(
            reference: new Reference('ref_1001'),
            providerRef: new ProviderRef('cs_test_123'),
            status: CanonicalPaymentStatus::pending,
            interaction: $redirect
        );

        $json = $result->jsonSerialize();

        $this->assertEquals('ref_1001', $json['reference']);
        $this->assertEquals('pending', $json['status']);
        $this->assertIsArray($json['interaction']);
        $this->assertEquals('redirect', $json['interaction']['type']);
        $this->assertEquals('https://checkout.stripe.com/pay/cs_test_123', $json['interaction']['url']);
    }

    public function test_virtual_account_provision_result_with_instructions_interaction(): void
    {
        $instructions = new Instructions(
            content: "# Complete Identity Verification\n\nPlease upload proof of address to finalize account assignment.",
            title: 'Kyc Requirements',
            presentation: Presentation::Dialog
        );

        $result = new VirtualAccountProvisionResult(
            status: VirtualAccountStatus::pending,
            interaction: $instructions
        );

        $json = $result->jsonSerialize();

        $this->assertEquals('pending', $json['status']);
        $this->assertIsArray($json['interaction']);
        $this->assertEquals('instructions', $json['interaction']['type']);
        $this->assertEquals('dialog', $json['interaction']['presentation']);
    }

    public function test_payout_result_with_interaction(): void
    {
        $redirect = new Redirect(
            url: 'https://verify.bank.com/2fa/payout'
        );

        $result = new PayoutResult(
            reference: new Reference('po_9900'),
            providerRef: new ProviderRef('pr_5500'),
            status: CanonicalPayoutStatus::requires_action,
            interaction: $redirect
        );

        $json = $result->jsonSerialize();

        $this->assertEquals('requires_action', $json['status']);
        $this->assertEquals('redirect', $json['interaction']['type']);
    }
}
