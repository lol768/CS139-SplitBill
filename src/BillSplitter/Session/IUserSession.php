<?php
namespace BillSplitter\Session;


/**
 * Wrap PHP's ugly session system in a slightly more OOP manner..
 *
 * @package BillSplitter\Session
 */
interface IUserSession {
    /**
     * Get a session item by key.
     *
     * @param string $key The key for this item.
     * @return mixed The value of the item in the sesion or null if not set.
     */
    public function get($key);

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
