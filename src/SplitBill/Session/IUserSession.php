<?php
namespace SplitBill\Session;


/**
 * Wrap PHP's ugly session system in a slightly more OOP manner..
 *
 * @package SplitBill\Session
 */
interface IUserSession {
    /**
     * Get a session item by key.
     *
     * @param string $key The key for this item.
     * @return mixed The value of the item in the session or null if not set.
     */
    public function get($key);

    /**
     * Get a session item by key, or the default if the session item does not exist.
     *
     * @param string $key The key for this item.
     * @param mixed $default Default.
     * @return mixed The value of the item in the session or the default if not set.
     */
    public function getOrDefault($key, $default);

    /**
     * Sets a session item by key with a value.
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function set($key, $value);

    /**
     * Returns whether or not the item exists in the session.
     *
     * @param string $key Session item key
     * @return bool
     */
    public function has($key);

    /**
     * Remove an item from the session.
     *
     * @param string $key Session item key
     * @return void
     */
    public function remove($key);
}
