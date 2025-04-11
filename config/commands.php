<?php

$commands = [
    //builtin commands
    'create:migration' => 'System\Cli\CreateMigration',
    'create:controller' => 'System\Cli\CreateController',
    'create:auth' => 'System\Cli\CreateAuthCommand',
    'create:model' => 'System\Cli\CreateModel',
    'create:package' => 'System\Cli\GeneratePackageCommand',
    'run:migration'    => 'System\Cli\SecMigration',
    'migrate'    => 'System\Cli\RunMigration',

    //Routing Cache Commands
    'routing:clear' => 'System\Cli\RoutingClear',
    'route:cache' => 'System\Cli\RouteCache',

    //Custom Commands
];

return $commands;