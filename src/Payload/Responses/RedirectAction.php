<?php declare(strict_types=1);

namespace PayKit\Payload\Responses;

final readonly class RedirectAction implements NextAction
{
    /**
     * @param array<string,string|int|float|bool|null> $params
     */
    public function __construct(
        public string $url,
        public string $method = 'GET',
        public array  $params = [],
    )
    {
    }

    public function type(): string
    {
        return 'redirect';
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type(),
            'url' => $this->url,
            'method' => strtoupper($this->method),
            'params' => $this->params,
        ];
    }
}