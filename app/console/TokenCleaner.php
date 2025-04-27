<?php

namespace Controller;

class TokenCleaner
{
    const TITLE = 'Hello there';
    const DESCRIPTION = 'wooo';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo 'Command executed: ' . self::TITLE . '\n';
        echo 'Parameters: val\n';
    }

    // Parameters:
    const PARAM_val = 'val';
}
