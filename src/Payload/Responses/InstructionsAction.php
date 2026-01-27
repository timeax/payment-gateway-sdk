<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

final readonly class InstructionsAction implements NextAction
{
    /**
     * @param array<int,string> $instructions
     */
    public function __construct(public array $instructions)
    {
    }

    public function type(): string
    {
        return 'instructions';
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type(),
            'instructions' => $this->instructions,
        ];
    }
}

