<?php


namespace SplitBill\Security;


interface IAntiRequestForgery {

    /**
     * @return string|null The user's CSRF token (or null if they don't have one).
     */
    public function hasCsrfTokenSet();

    /**
     * @return bool Whether or not the user has a CSRF token in their session.
     */
    public function getCurrentCsrfToken();

    /**
     * Generates a CSRF token and stores it in the user's session.
     * @return void
     */
    public function createCsrfTokenForUser();

}
