<?php declare(strict_types=1);

namespace PayKit\Contracts;

use PayKit\Payload\Common\GatewayConfig;
use PayKit\Payload\Requests\BeneficiaryCreateRequest;
use PayKit\Payload\Requests\BeneficiaryUpdateRequest;
use PayKit\Payload\Responses\BeneficiaryList;

interface PaymentGatewayBeneficiariesContract
{
    public function listBeneficiaries(?GatewayConfig $config = null): BeneficiaryList;

    public function createBeneficiary(BeneficiaryCreateRequest $request, ?GatewayConfig $config = null): bool;

    public function updateBeneficiary(BeneficiaryUpdateRequest $request, ?GatewayConfig $config = null): bool;
}