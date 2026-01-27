<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Requests\BeneficiaryCreateRequest;
use PayKit\Payload\Requests\BeneficiaryUpdateRequest;
use PayKit\Payload\Responses\BeneficiaryList;

interface PaymentGatewayBeneficiariesContract
{
    public function listBeneficiaries(?ConfigBag $config = null): BeneficiaryList;

    public function createBeneficiary(BeneficiaryCreateRequest $request, ?ConfigBag $config = null): bool;

    public function updateBeneficiary(BeneficiaryUpdateRequest $request, ?ConfigBag $config = null): bool;
}


