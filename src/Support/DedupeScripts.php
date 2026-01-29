<?php declare(strict_types=1);

namespace PayKit\Support;

use JsonException;
use PayKit\Payload\Common\GatewayScript;

final class DedupeScripts
{
    /**
     * @param array<int,GatewayScript> $scripts
     * @return array<int,GatewayScript>
     * @throws JsonException
     */
    public static function dedupe(array $scripts): array
    {
        $seen = [];
        $out = [];

        foreach ($scripts as $s) {
            $data = $s->jsonSerialize();

            $key = implode('|', [
                (string)($data['location'] ?? ''),
                (string)($data['src'] ?? ''),
                substr((string)($data['inline'] ?? ''), 0, 64),
                (string)($data['integrity'] ?? ''),
                (string)($data['crossorigin'] ?? ''),
                (string)($data['referrerPolicy'] ?? ''),
                json_encode($data['attributes'] ?? [], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ]);

            $hash = hash('sha256', $key);

            if (isset($seen[$hash])) {
                continue;
            }

            $seen[$hash] = true;
            $out[] = $s;
        }

        return $out;
    }
}

