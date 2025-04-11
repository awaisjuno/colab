<?php

namespace System\Helpers;

/**
 * Class Response
 *
 * A helper class to handle various types of HTTP responses such as JSON, redirect,
 * plain text, HTML views, and also provides utility methods for API success/error responses.
 *
 * @package System\Helpers
 */
class Response
{
    /**
     * Return a JSON response.
     *
     * @param mixed $data The data to be sent as JSON.
     * @param int $status The HTTP status code to be returned (default is 200).
     * @param array $headers Optional additional headers to send.
     *
     * @return void
     */
    public static function json($data, $status = 200, array $headers = [])
    {
        header('Content-Type: application/json');
        http_response_code($status);
        self::sendHeaders($headers);
        echo json_encode($data);
        exit();
    }

    /**
     * Return a success response for APIs.
     *
     * @param mixed $data The data payload.
     * @param string $message Message to describe the success.
     * @param int $status HTTP status code (default 200).
     *
     * @return void
     */
    public static function success($data = [], $message = 'Success', $status = 200)
    {
        self::json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Return an error response for APIs.
     *
     * @param string $message The error message.
     * @param int $status HTTP status code (default 400).
     * @param mixed $errors Optional validation or internal error details.
     *
     * @return void
     */
    public static function error($message = 'Something went wrong', $status = 400, $errors = null)
    {
        $response = [
            'status' => false,
            'message' => $message
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        self::json($response, $status);
    }

    /**
     * Validate and fetch data from a JSON request.
     *
     * @return array The parsed request body.
     */
    public static function validateJsonRequest()
    {
        if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
            self::error('Content-Type must be application/json', 415);
        }

        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            self::error('Invalid JSON format', 400);
        }

        return $data;
    }

    /**
     * Return a redirect response.
     *
     * @param string $url The URL to which the client will be redirected.
     * @param int $status The HTTP status code (default is 302).
     *
     * @return void
     */
    public static function redirect($url, $status = 302)
    {
        header("Location: $url", true, $status);
        exit();
    }

    /**
     * Return a plain text response.
     *
     * @param string $text The plain text to be sent.
     * @param int $status The HTTP status code (default is 200).
     *
     * @return void
     */
    public static function text($text, $status = 200)
    {
        header('Content-Type: text/plain');
        http_response_code($status);
        echo $text;
        exit();
    }

    /**
     * Return an HTML response by rendering a view.
     *
     * @param string $view The name of the view file to render (without extension).
     * @param array $data The data to be passed to the view.
     * @param int $status The HTTP status code (default is 200).
     *
     * @return void
     */
    public static function view($view, $data = [], $status = 200)
    {
        http_response_code($status);
        extract($data);
        require_once "views/{$view}.php";
        exit();
    }

    /**
     * Send additional HTTP headers.
     *
     * @param array $headers Array of key => value headers.
     *
     * @return void
     */
    protected static function sendHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {
            header("$key: $value");
        }
    }

    /**
     * Send basic CORS headers (optional).
     *
     * @return void
     */
    public static function enableCors()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }
}
