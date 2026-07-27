<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

enum BeneficiaryStatus: string
{
    case pending_validation = 'pending_validation';
    case active = 'active';
    case invalid = 'invalid';
    case disabled = 'disabled';
}
