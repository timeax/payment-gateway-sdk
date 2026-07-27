<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

enum AccountTransactionDirection: string
{
    case credit = 'credit';
    case debit = 'debit';
}
