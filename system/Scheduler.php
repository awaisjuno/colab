<?php
namespace System\Scheduler;

use System\Database\Connection;

class Scheduler
{
    private $db;

    public function __construct()
    {
        $this->db = new Connection();
    }

    public function run()
    {
        // Get all pending tasks that need to be run
        $tasks = $this->getPendingTasks();

        foreach ($tasks as $task) {
            // Check if it's time to run the task
            if (new \DateTime() >= new \DateTime($task['next_run'])) {
                $this->executeTask($task);
            }
        }
    }

    private function getPendingTasks()
    {
        $sql = "SELECT * FROM schedulers WHERE status = 'pending'";
        return $this->db->query($sql);
    }

    private function executeTask($task)
    {
        // Update status to running
        $this->db->query("UPDATE schedulers SET status = 'running' WHERE id = ?", [$task['id']]);

        // Get the class name
        $taskClass = "App\\Tasks\\" . $task['scheduler_class'];

        if (class_exists($taskClass)) {
            // Create instance and execute the task
            $taskInstance = new $taskClass();
            $taskInstance->execute();

            // After execution, update the next run time
            $this->updateNextRun($task);
        }

        // Mark task as completed
        $this->db->query("UPDATE schedulers SET status = 'completed' WHERE id = ?", [$task['id']]);
    }

    private function updateNextRun($task)
    {
        // For example, we want to run the task every 6 hours
        $nextRun = (new \DateTime())->modify('+6 hours')->format('Y-m-d H:i:s');
        $this->db->query("UPDATE schedulers SET next_run = ?, last_run = ? WHERE id = ?", [$nextRun, (new \DateTime())->format('Y-m-d H:i:s'), $task['id']]);
    }
}