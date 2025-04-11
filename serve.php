#!/usr/bin/env php
<?php

// Parse command line options
$options = getopt("", ["port::"]);
$port = isset($options['port']) ? $options['port'] : 8000;

// Display information
echo "Starting PHP development server on http://127.0.0.1:$port\n";

$command = "php -S 127.0.0.1:$port -t .";

exec($command);
