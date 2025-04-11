<?php

namespace System\Cli;

class CreateAuthCommand
{
    public function execute($args = [])
    {
        // AuthController
        $controllerContent = <<<EOD
<?php

namespace App\Controller;

use System\Controller;

class AuthController extends Controller
{
    public function login()
    {
        \$this->load->view('auth/login');
    }

    public function register()
    {
        \$this->load->view('auth/register');
    }

    public function logout()
    {
        // Logout logic here
        echo "User has been logged out.";
    }
}
EOD;

        // User model
        $modelContent = <<<EOD
<?php

namespace App\Model;

class User
{
    public function findByEmail(\$email)
    {
        // Lookup user by email from database
    }

    public function create(\$data)
    {
        // Insert user data into database
    }
}
EOD;

        // Login view
        $loginView = <<<EOD
<div class="container mt-5">
    <h2 class="mb-4">Login</h2>
    <form method="POST" action="/login">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required />
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
EOD;

        // Register view
        $registerView = <<<EOD
<div class="container mt-5">
    <h2 class="mb-4">Register</h2>
    <form method="POST" action="/register">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required />
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required />
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-success">Register</button>
    </form>
</div>
EOD;

        // Paths
        $controllerPath = 'app/Controller/AuthController.php';
        $modelPath = 'app/Model/User.php';
        $viewDir = 'app/View/auth';
        $loginPath = $viewDir . '/login.php';
        $registerPath = $viewDir . '/register.php';

        // Ensure directories exist
        if (!is_dir('app/Controller')) mkdir('app/Controller', 0777, true);
        if (!is_dir('app/Model')) mkdir('app/Model', 0777, true);
        if (!is_dir($viewDir)) mkdir($viewDir, 0777, true);

        // Write files
        file_put_contents($controllerPath, $controllerContent);
        file_put_contents($modelPath, $modelContent);
        file_put_contents($loginPath, $loginView);
        file_put_contents($registerPath, $registerView);

        echo "✅ Auth scaffolding created:\n";
        echo "├── Controller: AuthController\n";
        echo "├── Model: User\n";
        echo "└── Views: login.php, register.php\n";
    }
}
