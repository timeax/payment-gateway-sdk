<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

enum ScriptLocation: string
{
    case head = 'head';
    case body_start = 'body_start';
    case body_end = 'body_end';
}