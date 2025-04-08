<?php
namespace System\Helpers;

/**
 * Session class for managing session operations.
 *
 * This class provides methods for setting, getting, checking,
 * deleting session variables, and destroying all session data.
 *
 * @package System\Helpers
 */
class Session
{
    /**
     * Constructor starts the session if it has not been started already.
     *
     * PHP sessions are started when session_start() is called, and this
     * method ensures it is only called once.
     */
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a session variable.
     *
     * @param string $key The key name of the session variable.
     * @param mixed $value The value to store in the session.
     *
     * @return void
     */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get the value of a session variable.
     *
     * @param string $key The key name of the session variable.
     *
     * @return mixed|null Returns the value of the session variable or null if not set.
     */
    public function get(string $key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * Check if a session variable exists.
     *
     * @param string $key The key name of the session variable.
     *
     * @return bool True if the session variable exists, false otherwise.
     */
    public function exists(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Delete a session variable.
     *
     * @param string $key The key name of the session variable to delete.
     *
     * @return void
     */
    public function delete(string $key): void
    {
        if ($this->exists($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy all session variables and end the session.
     *
     * This method will clear all session data and destroy the session.
     * It is typically used when logging the user out.
     *
     * @return void
     */
    public function destroy(): void
    {
        session_unset();
        session_destroy();
    }
}
