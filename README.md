Colab CLI

Welcome to Colab CLI – a powerful command-line interface (CLI) framework designed to make developing and managing applications easier. Whether you're building a new web app, automating tasks, or working on a large-scale project, Colab CLI gives you the tools you need to streamline your workflow.
Table of Contents

    Introduction

    Features

    Installation

    Usage

    Contributing

    License

Introduction

Colab CLI is a comprehensive CLI framework for developers that helps in various development tasks such as routing, database migrations, model generation, controller creation, and more. It aims to simplify the process of building applications with an easy-to-use interface while providing extensive flexibility and customization options.
Features

    Command-Line Utilities: Automate your workflow with built-in CLI commands for controller generation, migration, and more.

    Routing System: Dynamic routing that supports custom middleware and advanced route handling.

    Database Migrations: Easily manage your database schema with built-in migration commands.

    Modular Architecture: Create custom commands, middleware, and tools to extend the CLI’s functionality.

    Easy Setup: Simple installation and setup process to get you started quickly.

Installation

To get started with Colab CLI, follow these steps:

    Clone the repository:

git clone https://github.com/awaisjuno/colab.git

Navigate into the project directory:

cd colab

Install dependencies (if applicable):

    composer install

    Set up your environment by configuring the appropriate settings in config/database.php.

Usage

Here are some basic commands to get you started:
Run the Application

To run the application, simply use the cli tool:

php cli <command>

For example, to run migrations:

php cli migrate

Generate Controllers, Models, and More

You can generate controllers, models, and migrations with the following commands:

php cli createController <ControllerName>
php cli createModel <ModelName>
php cli createMigration <MigrationName>

Route Caching

For better performance, Colab CLI allows you to cache your routes:

php cli route:cache

Contributing

We welcome contributions to Colab CLI! If you have an idea, fix, or feature you'd like to contribute, please fork the repository and create a pull request with your changes. Here’s how to get started:

    Fork the repository.

    Clone your fork locally.

    Create a new branch for your feature or fix.

    Make your changes.

    Test your changes.

    Push your changes and open a pull request.

Code Style

Please follow the PHP-FIG PSR-2 coding standards when contributing.
