<?php

use Illuminate\Support\Facades\File;

use function Pest\Laravel\artisan;

beforeEach(function () {
    // Clean up generated files before each test
    File::deleteDirectory(app_path('Leaflet'));
    File::deleteDirectory(app_path('Livewire'));
    File::delete(resource_path('views/livewire/store-locator.blade.php'));
});

afterEach(function () {
    File::deleteDirectory(app_path('Leaflet'));
    File::deleteDirectory(app_path('Livewire'));
    File::delete(resource_path('views/livewire/store-locator.blade.php'));
});

it('generates a php leaflet class', function () {
    artisan('make:leaflet', ['name' => 'StoreLocator'])
        ->expectsQuestion('What type of class would you like to generate?', 'PHP')
        ->assertSuccessful();

    $path = app_path('Leaflet/StoreLocator.php');

    expect(File::exists($path))->toBeTrue();

    $content = File::get($path);

    expect($content)
        ->toContain('namespace App\Leaflet;')
        ->toContain('class StoreLocator')
        ->toContain("LeafletMap::of('store-locator')")
        ->toContain('public function build(): Map');
});

it('generates a livewire leaflet component', function () {
    artisan('make:leaflet', ['name' => 'StoreLocator'])
        ->expectsQuestion('What type of class would you like to generate?', 'Livewire')
        ->assertSuccessful();

    $classPath = app_path('Livewire/StoreLocator.php');
    $viewPath = resource_path('views/livewire/store-locator.blade.php');

    expect(File::exists($classPath))->toBeTrue();
    expect(File::exists($viewPath))->toBeTrue();

    $classContent = File::get($classPath);

    expect($classContent)
        ->toContain('namespace App\Livewire;')
        ->toContain('class StoreLocator extends Component')
        ->toContain("LeafletMap::of('store-locator')")
        ->toContain('public function refreshMap()')
        ->toContain("view('livewire.store-locator')");

    $viewContent = File::get($viewPath);

    expect($viewContent)
        ->toContain('{!! $mapHtml !!}');
});

it('fails if php class already exists', function () {
    File::ensureDirectoryExists(app_path('Leaflet'));
    File::put(app_path('Leaflet/StoreLocator.php'), '<?php // existing');

    artisan('make:leaflet', ['name' => 'StoreLocator'])
        ->expectsQuestion('What type of class would you like to generate?', 'PHP')
        ->assertFailed();
});

it('fails if livewire component already exists', function () {
    File::ensureDirectoryExists(app_path('Livewire'));
    File::put(app_path('Livewire/StoreLocator.php'), '<?php // existing');

    artisan('make:leaflet', ['name' => 'StoreLocator'])
        ->expectsQuestion('What type of class would you like to generate?', 'Livewire')
        ->assertFailed();
});
