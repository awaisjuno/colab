<?php

namespace System\Helpers;

/**
 * Class Response
 *
 * A helper class to handle different types of HTTP responses such as JSON, redirect, plain text, and HTML.
 *
 * @package System\Helpers
 */
class Response
{
    /**
     * Return a JSON response.
     *
     * This method sets the appropriate headers and status code for a JSON response
     * and sends the provided data as a JSON-encoded string to the client.
     *
     * @param mixed $data The data to be sent as JSON.
     * @param int $status The HTTP status code to be returned (default is 200).
     *
     * @return void
     */
    public static function json($data, $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit();
    }

    /**
     * Return a redirect response.
     *
     * This method sends an HTTP redirect to the provided URL with the specified status code.
     *
     * @param string $url The URL to which the client will be redirected.
     * @param int $status The HTTP status code for the redirect (default is 302).
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
     * This method sets the appropriate headers and status code for a plain text response
     * and sends the provided text to the client.
     *
     * @param string $text The plain text to be sent in the response.
     * @param int $status The HTTP status code to be returned (default is 200).
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
     * This method renders a view file and passes the data to the view.
     * It sets the appropriate status code and returns the rendered HTML content.
     *
     * @param string $view The name of the view file to render (without extension).
     * @param array $data The data to be passed to the view.
     * @param int $status The HTTP status code to be returned (default is 200).
     *
     * @return void
     */
    public static function view($view, $data = [], $status = 200)
    {
        // Here, you could render a view file and pass the data
        // Assuming you have a method to render the view
        extract($data);
        require_once "views/{$view}.php";
        exit();
    }
}