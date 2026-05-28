<?php

namespace arielmejiadev\LeafletForLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

use function Laravel\Prompts\select;

class MakeLeafletCommand extends Command
{
    public $signature = 'make:leaflet {name : The name of the leaflet map class}';

    public $description = 'Generate a new Leaflet map class';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $name = $this->argument('name');
        $type = select('What type of class would you like to generate?', ['PHP', 'Livewire']);

        return $type === 'Livewire'
            ? $this->generateLivewire($name)
            : $this->generatePhp($name);
    }

    protected function generatePhp(string $name): int
    {
        $path = app_path("Leaflet/{$name}.php");

        if ($this->files->exists($path)) {
            $this->components->error("Class [{$name}] already exists.");

            return self::FAILURE;
        }

        $this->files->ensureDirectoryExists(app_path('Leaflet'));

        $stub = $this->files->get($this->stubPath('leaflet-map.php.stub'));

        $stub = str_replace(
            ['{{ class }}', '{{ id }}'],
            [$name, Str::kebab($name)],
            $stub,
        );

        $this->files->put($path, $stub);

        $this->components->info("Leaflet map class [{$path}] created successfully.");

        return self::SUCCESS;
    }

    protected function generateLivewire(string $name): int
    {
        $classPath = app_path("Livewire/{$name}.php");
        $viewName = Str::kebab($name);
        $viewPath = resource_path("views/livewire/{$viewName}.blade.php");

        if ($this->files->exists($classPath)) {
            $this->components->error("Livewire component [{$name}] already exists.");

            return self::FAILURE;
        }

        $this->files->ensureDirectoryExists(app_path('Livewire'));
        $this->files->ensureDirectoryExists(resource_path('views/livewire'));

        $classStub = $this->files->get($this->stubPath('leaflet-map-livewire.php.stub'));
        $classStub = str_replace(
            ['{{ class }}', '{{ id }}', '{{ view }}'],
            [$name, Str::kebab($name), $viewName],
            $classStub,
        );

        $viewStub = $this->files->get($this->stubPath('leaflet-map-livewire-view.blade.php.stub'));

        $this->files->put($classPath, $classStub);
        $this->files->put($viewPath, $viewStub);

        $this->components->info("Livewire component [{$classPath}] created successfully.");
        $this->components->info("Livewire view [{$viewPath}] created successfully.");

        return self::SUCCESS;
    }

    protected function stubPath(string $stub): string
    {
        return __DIR__."/stubs/{$stub}";
    }
}
