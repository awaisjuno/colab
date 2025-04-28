<?php

namespace System\Http;

/**
 * Class Response
 *
 * Represents an HTTP response, including content, headers, and status code.
 * Provides methods for setting content, headers, and sending the response to the client.
 *
 * @package System\Http
 */
class Response
{
    /**
     * @var string The content to be sent in the response body.
     */
    protected string $content = '';

    /**
     * @var int The HTTP status code for the response.
     */
    protected int $statusCode = 200;

    /**
     * @var array An associative array of headers to be sent with the response.
     */
    protected array $headers = [];

    /**
     * Response constructor.
     *
     * Initializes the response with content and status code.
     *
     * @param string $content The content to be sent in the response body.
     * @param int $statusCode The HTTP status code for the response. Defaults to 200.
     */
    public function __construct(string $content = '', int $statusCode = 200)
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
    }

    /**
     * Set the content of the response.
     *
     * @param string $content The content to be sent in the response body.
     *
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * Get the content of the response.
     *
     * @return string The content of the response body.
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set a header for the response.
     *
     * @param string $key The header name.
     * @param string $value The header value.
     *
     * @return void
     */
    public function setHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    /**
     * Send the response to the client by setting headers, status code, and content.
     *
     * @return void
     */
    public function send(): void
    {
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }

        http_response_code($this->statusCode);

        echo $this->content;
    }
}
