<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

enum CanonicalPayoutStatus: string
{
    case pending = 'pending';
    case processing = 'processing';
    case requires_action = 'requires_action';
    case succeeded = 'succeeded';
    case failed = 'failed';
    case cancelled = 'cancelled';
    case reversed = 'reversed';
}
