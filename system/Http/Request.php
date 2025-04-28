<?php

namespace System\Http;

/**
 * Class Request
 *
 * Encapsulates the HTTP request, including data from $_GET, $_POST, etc.
 * Provides methods for accessing request parameters.
 *
 * @package System\Http
 */
class Request
{
    /**
     * @var array Stores the request data (e.g., $_GET, $_POST, etc.).
     */
    protected array $data = [];

    /**
     * Request constructor.
     *
     * Initializes the request with provided data (typically from $_GET, $_POST).
     *
     * @param array $data An array of request data.
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Retrieve all data from the request.
     *
     * @return array An associative array of all request data.
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Retrieve a specific parameter from the request.
     *
     * @param string $key The key of the parameter to retrieve.
     * @param mixed $default The default value to return if the parameter does not exist.
     *
     * @return mixed The value of the parameter or the default value if not found.
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Check if a parameter exists in the request.
     *
     * @param string $key The key of the parameter to check.
     *
     * @return bool True if the parameter exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Capture the current request, merging $_GET and $_POST data.
     *
     * @return self A new instance of the Request class with the captured data.
     */
    public static function capture(): self
    {
        return new self(array_merge($_GET, $_POST));
    }
}
