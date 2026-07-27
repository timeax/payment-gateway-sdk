<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\Beneficiary;
use PayKit\Payload\Requests\BeneficiaryCreateRequest;
use PayKit\Payload\Requests\BeneficiaryUpdateRequest;
use PayKit\Payload\Responses\BeneficiaryCreateResult;
use PayKit\Payload\Responses\BeneficiaryList;
use PayKit\Payload\Responses\BeneficiaryUpdateResult;

interface PaymentGatewayBeneficiariesContract
{
    public function listBeneficiaries(?ConfigBag $config = null): BeneficiaryList;

    public function getBeneficiary(string $beneficiaryId, ?ConfigBag $config = null): ?Beneficiary;

    public function createBeneficiary(BeneficiaryCreateRequest $request, ?ConfigBag $config = null): BeneficiaryCreateResult;

    public function updateBeneficiary(BeneficiaryUpdateRequest $request, ?ConfigBag $config = null): BeneficiaryUpdateResult;

    public function deleteBeneficiary(string $beneficiaryId, ?ConfigBag $config = null): bool;
}
