<?php

namespace SplitBill\Security;

use SplitBill\Exception\InsecureTokenGenerationException;
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
     * @return bool Whether or not the user has a CSRF token in their session.
     */
    public function hasCsrfTokenSet() {
        return $this->session->has("__csrf_token");
    }

    /**
     * @return string|null The user's CSRF token (or null if they don't have one).
     */
    public function getCurrentCsrfToken() {
        return $this->session->get("__csrf_token");
    }

    /**
     * Generates a CSRF token and stores it in the user's session.
     * @throws InsecureTokenGenerationException If the token generation failed.
     */
    public function createCsrfTokenForUser() {
        $csrfToken = SecurityUtil::generateSecurityToken(64);
        $this->session->set("__csrf_token", $csrfToken);
    }
}
