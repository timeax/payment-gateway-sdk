<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

enum InboundTransferDecision: string
{
    case approve = 'approve';
    case decline = 'decline';
}
