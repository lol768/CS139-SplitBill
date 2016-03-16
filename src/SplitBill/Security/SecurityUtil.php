<?php

namespace SplitBill\Security;

use SplitBill\Exception\InsecureTokenGenerationException;

class SecurityUtil {

    /**
     * Timing-attack resistant string comparison function.
     *
     * @param string $expected The expected string.
     * @param string $actual The actual string.
     *
     * @return bool Whether or not they match.
     */
    public static function timingSafeComparison($expected, $actual) {
        $match = (strlen($expected) === strlen($actual));

        for ($i = 0; $i < strlen($actual); $i++) {
            if ($i < strlen($expected) && $expected[$i] !== $actual[$i]) {
                $match = false;
            }
        }
        return $match;
    }

    /**
     * Generate an unpredictable security token string.
     *
     * @param int|Number $bytes Number of bytes.
     * @return string Hex-encoded string.
     * @throws InsecureTokenGenerationException If the generation wasn't secure.
     */
    public static function generateSecurityToken($bytes=32) {
        $secure = false;
        $bytes = openssl_random_pseudo_bytes($bytes, $secure);
        if (!$secure) {
            throw new InsecureTokenGenerationException("Failed to generate random bytes in a cryptographically secure manner");
        }
        return bin2hex($bytes);
    }

}
