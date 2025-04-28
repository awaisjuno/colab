<?php
namespace System;

use System\Database\Connection;

/**
 * Class Scheduler
 *
 * Responsible for managing scheduled tasks, including running tasks, inserting new tasks, and updating task statuses.
 *
 * @package System
 */
class Scheduler
{
    /**
     * @var Connection $db The database connection instance.
     */
    private $db;

    /**
     * Scheduler constructor.
     *
     * Initializes the database connection.
     */
    public function __construct()
    {
        $this->db = new Connection();
    }

    /**
     * Run the scheduler to execute pending tasks.
     *
     * This method fetches pending tasks and executes them if it's time to run.
     */
    public function run(): void
    {
        $tasks = $this->getPendingTasks();

        foreach ($tasks as $task) {
            if (new \DateTime() >= new \DateTime($task['next_run'])) {
                $this->executeTask($task);
            }
        }
    }

    /**
     * Get all pending tasks that need to be executed.
     *
     * @return array The list of pending tasks.
     */
    private function getPendingTasks(): array
    {
        $sql = "SELECT * FROM schedulers WHERE status = 'pending'";
        return $this->db->query($sql);
    }

    /**
     * Execute the given task.
     *
     * This method updates the task status to 'running', executes the task,
     * and updates the status to 'completed' after execution.
     *
     * @param array $task The task to execute.
     */
    private function executeTask(array $task): void
    {
        $this->db->query("UPDATE schedulers SET status = 'running' WHERE id = ?", [$task['id']]);

        $taskClass = "App\\Tasks\\" . $task['scheduler_class'];

        if (class_exists($taskClass)) {
            $taskInstance = new $taskClass();
            $taskInstance->execute();
            $this->updateNextRun($task);
        }

        $this->db->query("UPDATE schedulers SET status = 'completed' WHERE id = ?", [$task['id']]);
    }

    /**
     * Update the next run time for a scheduled task.
     *
     * @param array $task The task whose next run time will be updated.
     */
    private function updateNextRun(array $task): void
    {
        $nextRun = (new \DateTime())->modify('+6 hours')->format('Y-m-d H:i:s');
        $this->db->query("UPDATE schedulers SET next_run = ?, last_run = ? WHERE id = ?", [
            $nextRun,
            (new \DateTime())->format('Y-m-d H:i:s'),
            $task['id']
        ]);
    }

    /**
     * Insert a new task into the scheduler table.
     *
     * This method schedules a new task by inserting it into the scheduler table
     * with an initial status of 'pending'.
     *
     * @param string $taskName The name of the task.
     * @param string|null $taskDescription A description of the task (optional).
     * @param \DateTime $scheduledAt When the task is scheduled to run.
     * @param string $schedulerClass The name of the class that will execute the task.
     *
     * @return bool Returns true if the task was successfully inserted, false otherwise.
     */
    public function insertTask(
        string $taskName,
        ?string $taskDescription,
        \DateTime $scheduledAt,
        string $schedulerClass
    ): bool {
        $sql = "INSERT INTO schedulers (task_name, task_description, scheduled_at, status, scheduler_class) 
                VALUES (:task_name, :task_description, :scheduled_at, 'pending', :scheduler_class)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':task_name', $taskName);
        $stmt->bindParam(':task_description', $taskDescription);
        $stmt->bindParam(':scheduled_at', $scheduledAt->format('Y-m-d H:i:s'));
        $stmt->bindParam(':scheduler_class', $schedulerClass);

        return $stmt->execute();
    }

    /**
     * Mark a task as completed.
     *
     * This method updates the status of a task to 'completed' when it has been successfully executed.
     *
     * @param int $taskId The ID of the task to mark as completed.
     *
     * @return bool Returns true if the task was successfully marked as completed, false otherwise.
     */
    public function completeTask(int $taskId): bool
    {
        $sql = "UPDATE schedulers SET status = 'completed' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$taskId]);
    }
}
