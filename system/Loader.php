<?php 

namespace System;

class Loader {

    public function __construct()
    {
        //$GLOBALS['helper'] = \System\Helpers\Helper::getInstance();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Helper();
        }
        return self::$instance;
    }

    public function view(string $view, array $data = []): void {
        (new \System\Controller)->view($view, $data);
    }
}

?>