<?php

namespace SplitBill\Security;

use SplitBill\Exception\InsecureCsrfTokenGenerationException;
use SplitBill\Session\IUserSession;

class AntiRequestForgeryManager implements IAntiRequestForgery {

    /**
     * @var IUserSession The session
     */
    private $session;

    /**
     * AntiRequestForgeryManager constructor.
     * @param IUserSession $session The session
     */
    public function __construct(IUserSession $session) {
        $this->session = $session;
    }

    /**
     * @return string|null The user's CSRF token (or null if they don't have one).
     */
    public function hasCsrfTokenSet() {
        return $this->session->has("__csrf_token");
    }

    /**
     * @return bool Whether or not the user has a CSRF token in their session.
     */
    public function getCurrentCsrfToken() {
        return $this->session->get("__csrf_token");
    }

    /**
     * Generates a CSRF token and stores it in the user's session.
     * @throws InsecureCsrfTokenGenerationException If the token generation failed.
     */
    public function createCsrfTokenForUser() {
        $wasSecure = false;
        $csrfToken = openssl_random_pseudo_bytes(64, $wasSecure);
        if (!$wasSecure) {
            throw new InsecureCsrfTokenGenerationException("Failed to generate random bytes in a cryptographically secure manner");
        }

        $this->session->set("__csrf_token", bin2hex($csrfToken));
    }
}
