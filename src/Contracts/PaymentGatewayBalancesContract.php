<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\BalanceAccount;
use PayKit\Payload\Common\Currency;

interface PaymentGatewayBalancesContract
{
    /**
     * @return array<BalanceAccount>
     */
    public function listBalances(?Currency $currency = null, ?ConfigBag $config = null): array;

    public function getBalance(string $balanceAccountId, ?ConfigBag $config = null): ?BalanceAccount;
}
