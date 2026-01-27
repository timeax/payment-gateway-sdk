<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

enum CanonicalPaymentStatus: string
{
    case pending = 'pending';
    case processing = 'processing';
    case succeeded = 'succeeded';
    case failed = 'failed';
    case cancelled = 'cancelled';
    case refunded = 'refunded';
}

