<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

enum PayoutMethod: string
{
    case bank = 'bank';
    case mobile_money = 'mobile_money';
    case wallet = 'wallet';
    case card = 'card';
    case crypto = 'crypto';
    case beneficiary = 'beneficiary';
    case provider_balance = 'provider_balance';
    case other = 'other';
}
