<?php declare(strict_types=1);

namespace PayKit\Drivers\Concerns;

use PayKit\Support\Redactor;

trait RedactsSecrets
{
    private ?Redactor $redactorInstance = null;

    protected function redactor(): Redactor
    {
        return $this->redactorInstance ??= new Redactor();
    }

    public function redactForLogs(mixed $payload): mixed
    {
        return $this->redactor()->redact($payload);
    }
}

