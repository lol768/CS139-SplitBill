<?php

namespace SplitBill\Security;

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

}
