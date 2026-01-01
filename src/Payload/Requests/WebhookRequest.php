<?php declare(strict_types=1);

namespace PayKit\Payload\Requests;

use JsonSerializable;

final readonly class WebhookRequest implements JsonSerializable
{
    /**
     * @param array<string,string|array<int,string>> $headers
     * @param array<string,string|array<int,string>> $query
     */
    public function __construct(
        public string $method,
        public string $path,
        public array  $headers,
        public string $body,
        public array  $query = [],
    )
    {
    }

    public function header(string $key, ?string $default = null): ?string
    {
        $k = strtolower($key);

        foreach ($this->headers as $hk => $hv) {
            if (strtolower($hk) !== $k) {
                continue;
            }

            if (is_array($hv)) {
                return (string)($hv[0] ?? $default);
            }

            return (string)$hv;
        }

        return $default;
    }

    public function jsonSerialize(): array
    {
        return [
            'method' => $this->method,
            'path' => $this->path,
            'headers' => $this->headers,
            'body' => $this->body,
            'query' => $this->query,
        ];
    }
}