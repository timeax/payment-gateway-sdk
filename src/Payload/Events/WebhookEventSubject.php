<?php declare(strict_types=1);

namespace PayKit\Payload\Events;

enum WebhookEventSubject: string
{
    case payment = 'payment';
    case refund = 'refund';
    case payout = 'payout';

    case virtual_account = 'virtual_account';

    // Optional/common extensions
    case dispute = 'dispute';
    case saved_method = 'saved_method';
    case other = 'other';
}