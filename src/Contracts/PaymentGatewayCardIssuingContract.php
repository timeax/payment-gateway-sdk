<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\VirtualCardRecord;
use PayKit\Payload\Requests\ListVirtualCardsRequest;
use PayKit\Payload\Requests\VirtualCardCreateRequest;
use PayKit\Payload\Requests\VirtualCardGetRequest;

interface PaymentGatewayCardIssuingContract
{
    public function createCard(VirtualCardCreateRequest $request, ?ConfigBag $config = null): VirtualCardRecord;

    public function getCard(VirtualCardGetRequest $request, ?ConfigBag $config = null): ?VirtualCardRecord;

    /**
     * @return array<VirtualCardRecord>
     */
    public function listCards(ListVirtualCardsRequest $request, ?ConfigBag $config = null): array;
}
