<?php

namespace SplitBill\Utilities;

use SplitBill\Security\SecurityUtil;

class PhpCompatibility {

    /**
     * PHP 5.3 compatible array_filter w/ ARRAY_FILTER_USE_KEY
     *
     * @param array $array Associative array to do the filter on.
     * @param \Closure $callback The callback. Needs to take one arg (the key) and return a boolean.
     * @return array The filtered array.
     */
    public static function arrayFilterKeys(array $array, $callback) {
        foreach ($array as $key => $value) {
            if (!$callback($key)) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    /**
     * Normally we'd use password_hash.
     *
     * @param string $password The user's password.
     * @return string
     */
    public static function makeBcryptHash($password) {
        $salt = // $2a == bcrypt
            '$2a$10$' . // 10 is the cost here. That's 2^10.
            self::getPasswordSaltString();
        return crypt($password, $salt);
    }

    /**
     * Normally we'd use password_hash.
     *
     * @param string $hash The user's hash from the database.
     * @param string $suppliedPassword The supplied password.
     * @return bool If the password is right or not.
     */
    public static function checkBcryptHash($hash, $suppliedPassword) {
        return SecurityUtil::timingSafeComparison($hash, crypt($suppliedPassword, $hash));
    }

    /**
     * @return string Gets a salt.
     * @see https://en.wikipedia.org/wiki/Bcrypt
     */
    private static function getPasswordSaltString() {
        // https://en.wikipedia.org/wiki/Bcrypt
        // "a 128-bit salt (base-64 encoded as 22 characters)"
        // 16*8 = 128, we then base64 and strip off the padding (=s).
        return substr(str_replace("+", ".", base64_encode(openssl_random_pseudo_bytes(16))), 0, -2);
    }

}
