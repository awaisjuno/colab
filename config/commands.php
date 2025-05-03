<?php

$commands = [
    //builtin commands
    'create:migration' => 'System\Cli\CreateMigration',
    'create:controller' => 'System\Cli\CreateController',
    'create:command' => 'System\Cli\CreateCustomCommand',
    'create:task' => 'System\Cli\CreateCustomTask',
    'create:auth' => 'System\Cli\CreateAuthCommand',
    'create:model' => 'System\Cli\CreateModel',
    'create:job' => 'System\Cli\CreateJob',
    'create:scheduler' => 'System\Cli\CreateScheduler',
    'create:package' => 'System\Cli\GeneratePackageCommand',
    'run:migration'    => 'System\Cli\SecMigration',
    'run:command'    => 'System\Cli\RunCommand',
    'migrate'    => 'System\Cli\RunMigration',

    //Routing Cache Commands
    'routing:clear' => 'System\Cli\RoutingClear',
    'route:cache' => 'System\Cli\RouteCache',

];

return $commands;