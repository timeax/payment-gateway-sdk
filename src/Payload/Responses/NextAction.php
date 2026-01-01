<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

use JsonSerializable;

interface NextAction extends JsonSerializable
{
    /** A stable discriminator like "redirect" | "inline" | "popup" | "qrcode" | "instructions". */
    public function type(): string;
}