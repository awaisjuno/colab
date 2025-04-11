<?php

namespace System\Cli;

use System\Database\Schema;

class SecMigration
{
    public function __construct()
    {
    }

    public function execute()
    {
        $migrationDir = ROOT_DIR . 'app/migrations/';

        if (!is_dir($migrationDir)) {
            echo "Error: Migrations directory does not exist.\n";
            return;
        }

        $migrationFiles = glob($migrationDir . '*.php');

        if (empty($migrationFiles)) {
            echo "No migrations found.\n";
            return;
        }

        foreach ($migrationFiles as $migrationFile) {
            require_once $migrationFile;

            $className = pathinfo($migrationFile, PATHINFO_FILENAME);

            // Check if the class exists and execute `up()` method
            if (class_exists($className)) {
                $migrationInstance = new $className();

                if (method_exists($migrationInstance, 'up')) {
                    echo "Running migration: {$className}...\n";
                    $migrationInstance->up();
                } else {
                    echo "Migration {$className} does not have an 'up' method.\n";
                }
            } else {
                echo "Class {$className} not found in {$migrationFile}.\n";
            }
        }

        echo "Migrations executed successfully.\n";
    }
}