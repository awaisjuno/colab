<?php

namespace System\Cli;

use System\Model;

/**
 * Class SyncJobsCommand
 *
 * Syncs job class files with the database.
 */
class SyncJobs
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * SyncJobs constructor.
     */
    public function __construct()
    {
        $this->model = new Model();
    }

    /**
     * Executes the synchronization of job classes.
     *
     * @return void
     */
    public function execute(): void
    {
        $jobDirectory = ROOT_DIR . 'app/jobs/';
        $jobFiles = $this->getJobFiles($jobDirectory);

        foreach ($jobFiles as $file) {
            $className = $this->getClassNameFromFile($file);

            if (!$className) {
                continue;
            }

            $existingJob = $this->getJobFromDatabase($className);

            if ($existingJob) {
                echo "Job class '{$className}' already exists in the database.\n";
            } else {
                $this->insertJobIntoDatabase($className);
                echo "Job class '{$className}' added to the database.\n";
            }
        }
    }

    /**
     * Retrieves all PHP job files in the given directory.
     *
     * @param string $directory
     * @return array
     */
    private function getJobFiles(string $directory): array
    {
        return glob($directory . '*.php');
    }

    /**
     * Extracts the class name from a PHP file.
     *
     * @param string $file
     * @return string
     */
    private function getClassNameFromFile(string $file): string
    {
        $fileContent = file_get_contents($file);
        preg_match('/class\s+([a-zA-Z0-9_]+)/', $fileContent, $matches);
        return $matches[1] ?? '';
    }

    /**
     * Checks if the job class already exists in the database.
     *
     * @param string $className
     * @return array|null
     */
    private function getJobFromDatabase(string $className)
    {
        return $this->model->select('jobs', ['job_class' => $className])->first();
    }

    /**
     * Inserts a new job class into the database.
     *
     * @param string $className
     * @return void
     */
    private function insertJobIntoDatabase(string $className): void
    {
        $this->model->insert('jobs', [
            'job_class' => $className,
            'job_data' => json_encode([]),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
