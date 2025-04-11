<?php

namespace System;

/**
 * Controller class handles loading of views and provides methods for rendering views in the application.
 */
class Controller {

    /**
     * @var \System\Loader
     * The loader instance used for loading various components in the application.
     */
    protected $load;

    /**
     * @var array
     * The configuration array loaded from the config file.
     */
    protected $config;

    /**
     * Controller constructor initializes the loader instance.
     */
    public function __construct() {
        // Initialize the loader
        $this->load = new \System\Loader();
        $this->config = require __DIR__ . '/../config/config.php';
    }

    /**
     * View method to load views and pass data to them.
     *
     * @param string $view The name of the view file to be loaded (without extension).
     * @param array $data (optional) An associative array of data to be extracted and passed to the view.
     *
     * @return void
     *
     * This method checks if the specified view file exists and if so,
     * extracts the data and includes the view file. If the view file does not exist,
     * an error message is displayed.
     */
    public function view(string $view, array $data = []): void {

        $viewFile = "app/views/{$view}.php";

        if (file_exists($viewFile)) {
            // Extract data to be used in the view
            extract($data);

            // Include the view file
            require_once $viewFile;
        } else {
            // View file not found, display error message
            echo "View file {$view}.php not found.";
        }
    }

    /**
     * Retrieves data from the POST array.
     *
     * This method provides an easy way to access the POST data in the controller.
     * If a specific key is provided, it returns the value associated with that key.
     * If no key is provided, it returns the entire POST array.
     *
     * @param string|null $key The key for which the value needs to be retrieved from the POST array.
     *                         If no key is provided, the entire POST array is returned.
     * @return mixed|null Returns the value associated with the provided key or the entire POST array if no key is provided.
     *                   If the key is not found, it returns null.
     */
    public function post($key = null) {
        // If no key is passed, return the entire POST array
        if ($key === null) {
            return $_POST;
        }

        // Return specific key from POST data
        return isset($_POST[$key]) ? trim($_POST[$key]) : null;
    }

}
?>
