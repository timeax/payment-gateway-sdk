<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

enum PayoutSourceType: string
{
    case provider_balance = 'provider_balance';
    case virtual_account = 'virtual_account';
    case balance_account = 'balance_account';
}
