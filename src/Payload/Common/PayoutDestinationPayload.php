<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

interface PayoutDestinationPayload extends JsonSerializable
{
    public function method(): PayoutMethod;
}
