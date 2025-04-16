🧠 Colab — Microservice Architecture Framework

Colab is a conceptual PHP-based micro-framework designed around microservice architecture principles and inspired by cloud-native application development patterns.

It provides a lightweight, modular, and scalable structure to build modern applications — whether monolithic in nature or broken into services. Colab focuses on separation of concerns, code readability, and extensibility, allowing developers to build applications the right way from the ground up.
🚀 Key Features

    Microservice-ready architecture

    Routing and Middleware system

    Service Provider system

    Lightweight and fast

    Scalable for cloud deployments (AWS, GCP, Azure)

    Custom database abstraction and logging system

    Support for clean MVC pattern

    Developer-friendly configuration and autoloading

🔄 Colab Request Cycle

Here’s how a typical request is processed in Colab:

    Bootstrap & Entry Point

        Request hits the entry file index.php

        It initializes constants like ROOT_DIR and autoloads required files

    Processing Layer

        The Processing class is instantiated

        It sets up:

            Error handling

            Database connection

            Service Providers via ServiceLoader

            Routing

    ServiceLoader

        Loads providers defined in config/services.php

        Calls their register() method to bind services like routes, events, etc.

    Routing Layer

        Extracts the URI from the request

        Matches it against registered routes

        Applies any middleware if configured

        Forwards the request to the corresponding controller and method

    Controller & Business Logic

        Controller executes business logic

        Loads model(s) or services

        Returns response

    Error Handling & Logging

        Any uncaught exceptions or errors are logged

        Errors are logged both to a file and optionally a bug_reporting database table

🚀 Colab CLI

composer create-project colab/cli my-project v1


Create Controller

php cli create:controller PagesController


Create Model

php cli create:model User


php cli migrate 
