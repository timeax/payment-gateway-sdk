<?php declare(strict_types=1);

namespace PayKit\Tests\Payload;

use PayKit\Payload\Common\InboundTransferDecision;
use PayKit\Payload\Requests\InboundTransferDecisionRequest;
use PayKit\Payload\Responses\InboundTransferDecisionResult;
use PHPUnit\Framework\TestCase;

final class InboundTransferApprovalTest extends TestCase
{
    public function test_inbound_transfer_decision_serialization(): void
    {
        $request = new InboundTransferDecisionRequest(
            transferId: 'tr_deposit_55',
            decision: InboundTransferDecision::approve,
            reason: 'KYC verified'
        );

        $json = $request->jsonSerialize();

        $this->assertEquals('tr_deposit_55', $json['transferId']);
        $this->assertEquals('approve', $json['decision']);
        $this->assertEquals('KYC verified', $json['reason']);

        $result = new InboundTransferDecisionResult(
            success: true,
            status: 'approved'
        );

        $this->assertTrue($result->success);
        $this->assertEquals('approved', $result->status);
    }
}
