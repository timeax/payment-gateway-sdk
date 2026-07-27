<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

enum VirtualAccountUsage: string
{
    case single_use = 'single_use';
    case reusable = 'reusable';
}
