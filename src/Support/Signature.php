<?php declare(strict_types=1);

namespace PayKit\Support;

final class Signature
{
    public static function sign(string $payload, string $secret, string $algo = 'sha256'): string
    {
        return hash_hmac($algo, $payload, $secret);
    }

    /**
     * Verifies a hex HMAC signature.
     * Accepts signature with optional prefixes like "sha256=...." and strips them safely.
     */
    public static function verify(string $payload, string $provided, string $secret, string $algo = 'sha256'): bool
    {
        $provided = trim($provided);

        // Common "algo=hex" format (Stripe/GitHub-style)
        $pos = strpos($provided, '=');
        if ($pos !== false) {
            $maybeAlgo = strtolower(substr($provided, 0, $pos));
            $maybeSig = substr($provided, $pos + 1);
            if ($maybeAlgo !== '') {
                $algo = $maybeAlgo;
                $provided = $maybeSig;
            }
        }

        $expected = self::sign($payload, $secret, $algo);

        return hash_equals($expected, $provided);
    }
}

