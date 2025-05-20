<?php

namespace System\Cli;

class GeneratePackageCommand
{
    public function __construct()
    {
    }

    public function execute()
    {
        $packageName = $GLOBALS['argv'][2];
        $this->handle($packageName);
    }

    public function handle($packageName)
    {
        if (empty($packageName)) {
            echo "Error: Package name is required.\n";
            return;
        }

        $packagePath = __DIR__ . "/../../vendor/{$packageName}";

        if (file_exists($packagePath)) {
            echo "Error: Package '{$packageName}' already exists.\n";
            return;
        }

        try {
            mkdir($packagePath, 0777, true);
            mkdir("{$packagePath}/src", 0777, true);
            mkdir("{$packagePath}/src/Controller", 0777, true);
            mkdir("{$packagePath}/src/Model", 0777, true);
            mkdir("{$packagePath}/src/View", 0777, true);
            mkdir("{$packagePath}/src/Providers", 0777, true);

            $this->createServiceProviderFile($packagePath, $packageName);
            $this->createControllerFile($packagePath, $packageName);
            $this->createModelFile($packagePath, $packageName);
            $this->createComposerJsonFile($packagePath, $packageName);

            echo "Package '{$packageName}' has been created successfully.\n";
        } catch (\Exception $e) {
            echo "Error: Could not create the package '{$packageName}'. " . $e->getMessage() . "\n";
        }
    }

    protected function createServiceProviderFile(string $packagePath, string $packageName): void
    {
        $serviceProviderContent = "<?php

namespace {$packageName}\\Providers;

use System\\Providers\\ServiceProvider;

class {$packageName}ServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
    }
}
";

        file_put_contents("{$packagePath}/src/Providers/{$packageName}ServiceProvider.php", $serviceProviderContent);
    }

    protected function createControllerFile(string $packagePath, string $packageName): void
    {
        $controllerContent = "<?php

namespace {$packageName}\\Controller;

use System\\Controller;
use {$packageName}\\Model\\ExampleModel;

class ExampleController extends Controller
{
    protected \$model;

    public function __construct()
    {
        parent::__construct();
        \$this->model = new ExampleModel();
    }

    public function index()
    {
        \$this->load->view('{$packageName}/index');
    }
}
";

        file_put_contents("{$packagePath}/src/Controller/ExampleController.php", $controllerContent);
    }

    protected function createModelFile(string $packagePath, string $packageName): void
    {
        $modelContent = "<?php

namespace {$packageName}\\Model;

use System\\Model;

class ExampleModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function fetchExampleData()
    {
        return \$this->select('example_table')->get();
    }
}
";

        file_put_contents("{$packagePath}/src/Model/ExampleModel.php", $modelContent);
    }

    protected function createComposerJsonFile(string $packagePath, string $packageName): void
    {
        $composerName = strtolower(str_replace('\\', '-', $packageName));

        $composerJson = [
            'name' => "vendor/{$composerName}",
            'description' => "A basic package for {$packageName}.",
            'type' => 'library',
            'license' => 'MIT',
            'autoload' => [
                'psr-4' => [
                    "{$packageName}\\" => 'src/',
                ],
            ],
            'authors' => [
                [
                    'name' => 'Your Name',
                    'email' => 'your.email@example.com',
                ],
            ],
            'require' => new \stdClass(),
        ];

        file_put_contents("{$packagePath}/composer.json", json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public static string $description = 'Create Package Command';
}
