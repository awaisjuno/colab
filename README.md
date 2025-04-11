🚀 Colab CLI

Colab CLI is a lightweight, high-performance PHP 8 framework inspired by the elegance of Laravel and the simplicity of CodeIgniter 3. It follows a custom-built MVC architecture with CLI tools, middleware support, service providers, and easy-to-use routing.

📦 Installation

Anyone can get started with a single command:

composer create-project colab/cli my-project v1

This will create a fresh Colab CLI project in the my-project folder.
🔄 Request Cycle of Colab

    The request hits public/index.php.

    The request is forwarded to the Process class.

    The Process class loads Service Providers, including any registered packages.

    The request is passed to the routing system defined in config/routes.php.

    Middleware is checked (if any).

    The appropriate Controller and Method are executed.

    The view is rendered using $this->load->view().

⚙️ Configuration
📁 Database

Edit your database settings in:

config/database.php

Example:

return [
    'host' => 'localhost',
    'database' => 'my_db',
    'username' => 'root',
    'password' => '',
];

💻 CLI Usage

Colab provides several artisan-style CLI commands:

php cli create:controller BlogController
php cli create:model Blog
php cli create:migration create_blogs_table
php cli create:auth
php cli run:migration
php cli migrate

# Routing Cache Commands
php cli routing:clear
php cli route:cache

Command Mappings

[
    'create:migration' => 'System\Cli\CreateMigration',
    'create:controller' => 'System\Cli\CreateController',
    'create:auth' => 'System\Cli\CreateAuthCommand',
    'create:model' => 'System\Cli\CreateModel',
    'create:package' => 'System\Cli\GeneratePackageCommand',
    'run:migration' => 'System\Cli\SecMigration',
    'migrate' => 'System\Cli\RunMigration',
    'routing:clear' => 'System\Cli\RoutingClear',
    'route:cache' => 'System\Cli\RouteCache',
];

🧱 Routing & Controllers

Routing is defined in:

config/routes.php

Example:

return [
    '/gg' => [
        'controller' => 'Pages',
        'method' => 'index',
        'middleware' => ['Authenticateff']
    ],
    'home' => [
        'controller' => 'Pages',
        'method' => 'show',
    ]
];

Controller Example

namespace App\Controller;
use System\Controller;

class Pages extends Controller
{
    public function index()
    {
        $this->load->view('pages/header');
        $this->load->view('landing');
        $this->load->view('pages/footer');
    }
}

🖼️ Views & Form Helpers

Render views using:

$this->load->view('user/signin');

✅ Built-in Form Helpers

<?= form_open('/login') ?>
    <?= form_input('email', '', ['placeholder' => 'Email']) ?>
    <?= form_password('password', ['placeholder' => 'Password']) ?>
    <?= form_submit('Login') ?>
<?= form_close() ?>

These are defined in system/lib/form_helper.php.
🔐 User Authentication

Generate authentication scaffold with:

php cli create:auth

This will generate:

    app/Controller/AuthController.php

    app/Model/User.php

    app/View/auth/login.php

    app/View/auth/register.php

You can now extend the User model and add validation, session management, and encryption.
🧾 Directory Structure

colab-cli/
├── app/
│   ├── Controller/       # Application controllers
│   ├── Model/            # Database models and business logic
│   ├── Middleware/       # Middleware classes
│   ├── Service/          # Business logic and helpers
│   ├── View/             # Front-end templates
├── config/
│   ├── app.php           # App settings
│   ├── database.php      # DB config
│   └── routes.php        # Routing map
├── system/
│   ├── driver/           # Core services like DB, Loader
│   ├── lib/              # Helper libraries
│   └── middleware/       # Global middleware
├── public/               # Public access folder
│   └── index.php         # Entry point
├── assets/               # CSS, JS, images
└── cache/
    └── routes.cache.php  # Cached routes

✨ Features

    PHP 8 Support

    Lightweight & Fast

    Clean MVC Structure

    Custom CLI Tooling

    Laravel-style Migrations

    Middleware Support

    Service Providers

    Route Caching

    Authentication Scaffold

    View & Form Helpers

    Package Generator Support

👨‍💻 Created By

Awais Juno
Software Developer
