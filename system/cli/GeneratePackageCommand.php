<?php

namespace System\Cli;

class GeneratePackageCommand
{
    public function __construct()
    {
    }

    public function execute()
    {
        // The package name is expected as the first argument when running the CLI command.
        $packageName = $GLOBALS['argv'][2];  // Getting the package name from command line argument.

        $this->handle($packageName);  // Calling handle() method to handle the package creation logic
    }

    public function handle($packageName)
    {
        // Validate package name.
        if (empty($packageName)) {
            echo "Error: Package name is required.\n";
            return;
        }

        // Define the directory path for the package inside vendor directory
        $packagePath = __DIR__ . "/../../vendor/{$packageName}";

        // Check if the package already exists.
        if (file_exists($packagePath)) {
            echo "Error: Package '{$packageName}' already exists.\n";
            return;
        }

        // Create the package folder structure
        try {
            mkdir($packagePath, 0777, true);
            mkdir("{$packagePath}/src", 0777, true);
            mkdir("{$packagePath}/src/Controllers", 0777, true);
            mkdir("{$packagePath}/src/Models", 0777, true);
            mkdir("{$packagePath}/src/Views", 0777, true);
            mkdir("{$packagePath}/routes", 0777, true);
            mkdir("{$packagePath}/src/Providers", 0777, true); // Ensure the Providers directory exists

            // Create the necessary files
            $this->createServiceProviderFile($packagePath, $packageName);
            $this->createRoutesFile($packagePath, $packageName);  // Pass $packageName to method
            $this->createControllerFile($packagePath, $packageName);  // Pass $packageName to method
            $this->createModelFile($packagePath, $packageName);  // Pass $packageName to method
            $this->createPackageConfigFile($packagePath, $packageName); // Add configuration file

            echo "Package '{$packageName}' has been created successfully.\n";
        } catch (\Exception $e) {
            echo "Error: Could not create the package '{$packageName}'. " . $e->getMessage() . "\n";
        }
    }

    protected function createServiceProviderFile($packagePath, $packageName)
    {
        $serviceProviderContent = "<?php

namespace {$packageName}\\Providers;

use Illuminate\\Support\\ServiceProvider;

class {$packageName}ServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind your services or packages here.
    }

    public function boot()
    {
        // Perform any bootstrapping tasks.
    }
}
";

        file_put_contents("{$packagePath}/src/Providers/{$packageName}ServiceProvider.php", $serviceProviderContent);
    }

    protected function createRoutesFile($packagePath, $packageName)
    {
        $routesContent = "<?php

return [
    '/home' => ['controller' => '{$packageName}\\Controllers\\User', 'method' => 'index'],
    'about' => ['controller' => '{$packageName}\\Controllers\\Pages', 'method' => 'about'],
    'posts/{id}' => ['controller' => '{$packageName}\\Controllers\\Posts', 'method' => 'show'],
    'login' => ['controller' => '{$packageName}\\Controllers\\Auth', 'method' => 'login'],
    'register' => ['controller' => '{$packageName}\\Controllers\\Auth', 'method' => 'register'],
];
";

        file_put_contents("{$packagePath}/routes/web.php", $routesContent);
    }

    protected function createControllerFile($packagePath, $packageName)
    {
        $controllerContent = "<?php

namespace {$packageName}\\Controllers;

use App\\Http\\Controllers\\Controller;

class ExampleController extends Controller
{
    public function index()
    {
        return view('{$packageName}::index');
    }
}
";

        file_put_contents("{$packagePath}/src/Controllers/ExampleController.php", $controllerContent);
    }

    protected function createModelFile($packagePath, $packageName)
    {
        $modelContent = "<?php

namespace {$packageName}\\Models;

use Illuminate\\Database\\Eloquent\\Model;

class ExampleModel extends Model
{
    protected \$table = '{$packageName}_example';
}
";

        file_put_contents("{$packagePath}/src/Models/ExampleModel.php", $modelContent);
    }

    protected function createPackageConfigFile($packagePath, $packageName)
    {
        $configContent = "<?php

return [
    'name' => '{$packageName}',
    'version' => '1.0.0',
    'description' => 'A basic package for {$packageName}.',
    'author' => 'Your Name',
];
";

        file_put_contents("{$packagePath}/config.php", $configContent);
    }
}
