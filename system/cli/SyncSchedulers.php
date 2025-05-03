<?php

namespace System\Cli;

use System\Model;

/**
 * Class SyncSchedulers
 *
 * Syncs scheduler class files with the database.
 */
class SyncSchedulers
{
    protected $model;

    public function __construct()
    {
        $this->model = new Model();
    }

    public function execute(): void
    {
        $classDirectory = ROOT_DIR . 'app/schedulers/';
        $classFiles = $this->getClassFiles($classDirectory);

        foreach ($classFiles as $file) {
            $className = $this->getClassNameFromFile($file);

            if (!$className) {
                echo "Skipped invalid or unreadable file: {$file}\n";
                continue;
            }

            $existingScheduler = $this->getSchedulerFromDatabase($className);

            if ($existingScheduler) {
                echo "Scheduler class '{$className}' already exists in the database.\n";
            } else {
                $this->insertSchedulerIntoDatabase($className);
                echo "Scheduler class '{$className}' added to the database.\n";
            }
        }
    }

    private function getClassFiles(string $directory): array
    {
        return glob($directory . '*.php');
    }

    private function getClassNameFromFile(string $file): string
    {
        $fileContent = file_get_contents($file);

        $namespace = '';
        if (preg_match('/namespace\s+([^;]+);/', $fileContent, $nsMatches)) {
            $namespace = trim($nsMatches[1]) . '\\';
        }

        if (preg_match('/^\s*(?:final\s+|abstract\s+)?class\s+([a-zA-Z0-9_]+)/m', $fileContent, $matches)) {
            return $namespace . $matches[1];
        }

        return '';
    }

    private function getSchedulerFromDatabase(string $className)
    {
        return $this->model->select('schedulers', ['scheduler_class' => $className])->first();
    }

    private function insertSchedulerIntoDatabase(string $className): void
    {
        $this->model->insert('schedulers', [
            'scheduler_class' => $className,
            'status' => 'pending',
            'next_run' => null,
            'last_run' => null,
            'description' => 'Scheduled job for ' . $className,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
