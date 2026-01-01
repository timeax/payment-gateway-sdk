<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

enum PayoutMethod: string
{
    case bank = 'bank';
    case wallet = 'wallet';
    case card = 'card';
    case crypto = 'crypto';
    case other = 'other';
}