<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

enum VirtualAccountStatus: string
{
    case pending = 'pending';
    case active = 'active';
    case suspended = 'suspended';
    case deactivated = 'deactivated';
    case expired = 'expired';
    case failed = 'failed';
}
