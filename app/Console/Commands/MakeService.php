<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new service class';

    public function handle()
    {
        $name = $this->argument('name');

        // Normalize path
        $name = str_replace('\\', '/', $name);
        $path = app_path("Services/{$name}.php");
        $namespace = 'App\\Services\\' . str_replace('/', '\\', dirname($name));
        $class = class_basename($name);

        // Check if file exists
        if (File::exists($path)) {
            $this->error('Service already exists!');
            return 1;
        }

        // Create directory
        File::ensureDirectoryExists(dirname($path));

        // Create file content
        $stub = <<<EOT
<?php

namespace $namespace;

class $class
{
    //
}
EOT;

        File::put($path, $stub);
        $this->info("Service {$class} created successfully.");
        return 0;
    }
}
