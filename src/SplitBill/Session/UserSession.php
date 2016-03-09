<?php

namespace SplitBill\Session;
use SplitBill\IApplication;

/**
 * Wrap PHP's ugly session system in a slightly more OOP manner..
 *
 * @package SplitBill\Session
 */
class UserSession implements IUserSession {

    public function __construct(IApplication $app) {
        $config = $app->getConfig();

        ini_set('session.cookie_httponly', true);
        ini_set('session.cookie_path', $config['cookies']['path']);
        session_name($config['cookies']['name']);

        if (session_id() === '') {
            session_start();
        }
    }

    public function get($key) {
        if (array_key_exists($key, $_SESSION)) {
            return json_decode($_SESSION[$key], true);
        }
        return null;
    }

    public function set($key, $value) {
        $_SESSION[$key] = json_encode($value);
    }

    public function has($key) {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * Remove an item from the session.
     *
     * @param string $key Session item key
     * @return void
     */
    public function remove($key) {
        if (array_key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
        }
    }
}