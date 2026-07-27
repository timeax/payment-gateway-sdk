<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

enum VirtualAccountPurpose: string
{
    case collection = 'collection';
    case payment = 'payment';
    case balance = 'balance';
}
