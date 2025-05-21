<p align="center">
  <img src="https://github.com/user-attachments/assets/3a2d4597-6f27-4a6f-b31e-2f0f2551924b" width="500"/>
</p>

Colab Microservice Architecture Framework

Colab is a conceptual PHP-based micro-framework designed around microservice architecture principles and inspired by cloud-native application development patterns.

It provides a lightweight, modular, and scalable structure to build modern applications whether monolithic in nature or broken into services. Colab focuses on separation of concerns, code readability, and extensibility, allowing developers to build applications the right way from the ground up.

Colab CLI

To Install Colab
composer create-project colab/cli my-project

To Create Controller
php cli create:controller controller_name

To Create Model
php cli create:model model_name

To Create Migration
php cli create:migration table_name


Colab custom Job & Scheduler system is designed to provide developers with asynchronous task execution, scheduled automation, and real-time visibility into background processes.

How It Works
At its core, the Job & Scheduler system is powered by:
A Jobs table to register all available job classes.
A Schedulers table to define, configure, and queue scheduled tasks (like cron jobs).
A Runner script that listens and processes jobs in queue one by one.
A sync command that auto-discovers job and scheduler classes and updates the database mapping accordingly.

Developer Workflow

To Create a new job
php cli create:job SendWelcomeEmail

To Sync Jobs classes
php cli sync:jobs

To Create a new Schedulers
php cli create:schedulers RedisCleaning

To Sync Schedulers classes
php cli sync:schedulers

Sync Scan schedulers & Jobs directories
Register any new classes into table
Update Mapping in the Backend Processing
Ensure class to database mapping is sync before runtime.

Backend Terminology
Job Runner: The System that proccess each job sequentially (queue).
Scheduler Daemon: Handles repeating tasks based on time.
Status Logger: Each job run is logged with real-time status: Pending, Running, Completed, or Failed.

Admin Panel Features
The admin panel is designed not just for control but for monitoring and debugging:
Job & Scheduler Management:
•	Enable/Disable any job or scheduler
•	Modify execution time or priority
•	View job details, next run time, and assigned class
Real Time Stats:
•	Watch currently running jobs and scheduled tasks
•	See execution time, success rate, last run timestamp
•	Monitor failed jobs with error logs and stack traces
•	Re-queue failed jobs with one click
Sequence Control:
•	Reorder jobs or schedule priorities via drag & drop
•	Admin can adjust execution queue directly
