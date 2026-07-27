<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

enum VirtualCardStatus: string
{
    case pending = 'pending';
    case active = 'active';
    case frozen = 'frozen';
    case terminated = 'terminated';
    case failed = 'failed';
}
