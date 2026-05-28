<?php

namespace arielmejiadev\LeafletForLaravel;

use arielmejiadev\LeafletForLaravel\Commands\MakeLeafletCommand;
use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LeafletForLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('leaflet-for-laravel')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_leaflet_for_laravel_table')
            ->hasCommand(MakeLeafletCommand::class);
    }

    public function packageBooted(): void
    {
        $this->registerBladeDirectives();
    }

    protected function registerBladeDirectives(): void
    {
        Blade::directive('leafletStyles', function () {
            return "<?php echo \arielmejiadev\LeafletForLaravel\LeafletMap::styles(); ?>";
        });

        Blade::directive('leafletMap', function (string $expression) {
            return "<?php echo ({$expression})->toHtml(); ?>";
        });

        Blade::directive('leafletGlyphStyles', function (string $expression) {
            $expression = trim($expression) ?: '[]';

            return "<?php echo \arielmejiadev\LeafletForLaravel\LeafletMap::glyphStyles({$expression}); ?>";
        });
    }
}
