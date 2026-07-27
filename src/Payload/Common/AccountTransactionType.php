<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

enum AccountTransactionType: string
{
    case payment = 'payment';
    case payout = 'payout';
    case refund = 'refund';
    case fee = 'fee';
    case transfer = 'transfer';
    case adjustment = 'adjustment';
    case hold = 'hold';
    case release = 'release';
    case topup = 'topup';
    case withdrawal = 'withdrawal';
}
