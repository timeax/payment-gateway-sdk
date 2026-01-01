<?php declare(strict_types=1);

namespace PayKit\Support;

use DateMalformedStringException;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

final readonly class Clock
{
    public function __construct(private ?DateTimeZone $tz = null)
    {
    }

    /**
     * @throws DateMalformedStringException
     */
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', $this->tz);
    }

    public function nowIso(): string
    {
        return $this->now()->format(DateTimeInterface::ATOM);
    }

    public function iso(DateTimeInterface $dt): string
    {
        return $dt->format(DateTimeInterface::ATOM);
    }
}