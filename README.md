# Leaflet for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/arielmejiadev/leaflet-for-laravel.svg?style=flat-square)](https://packagist.org/packages/arielmejiadev/leaflet-for-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/arielmejiadev/leaflet-for-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/arielmejiadev/leaflet-for-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/arielmejiadev/leaflet-for-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/arielmejiadev/leaflet-for-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/arielmejiadev/leaflet-for-laravel.svg?style=flat-square)](https://packagist.org/packages/arielmejiadev/leaflet-for-laravel)

A fluent, expressive PHP API for rendering [Leaflet.js](https://leafletjs.com) maps in Laravel — no JavaScript required.

**[Full Documentation](https://arielmejia.dev/leaflet-for-laravel/)**

## Quick Start

### 1. Install

```bash
composer require arielmejiadev/leaflet-for-laravel
```

### 2. Build a map in your controller

```php
use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Marker;

class LandmarkController extends Controller
{
    public function index()
    {
        $map = LeafletMap::of('landmarks')
            ->center(40.6892, -74.0445)
            ->zoom(15)
            ->marker(40.6892, -74.0445, fn (Marker $pin) => $pin
                ->popup('<b>Statue of Liberty</b><br>New York, NY')
                ->icon('blue')
            );

        return view('landmarks.index', compact('map'));
    }
}
```

### 3. Render in Blade

```blade
<!DOCTYPE html>
<html>
<head>
    @leafletStyles
</head>
<body>
    @leafletMap($map)
</body>
</html>
```

That's it — two Blade directives and you have a live, interactive map.

## Features

- **Pure PHP** — write maps in PHP, zero JavaScript needed
- **Fluent API** — chainable methods like `->center()->zoom()->marker()`
- **Markers** — popups, tooltips, draggable pins, colored icons, circles
- **Glyph Icons** — Font Awesome & Material Design icons rendered on pins
- **Layer Control** — multiple base map styles + toggleable overlay groups
- **GeoJSON** — render points, lines, and feature collections
- **Blade Directives** — `@leafletStyles` + `@leafletMap($map)`
- **Livewire Ready** — scaffold components with `php artisan make:leaflet`
- **Macros & Conditionals** — extend with `Map::macro()`, branch with `->when()`
- **Multiple Maps** — independent instances on the same page

## Installation

```bash
composer require arielmejiadev/leaflet-for-laravel
```

Optionally publish the config:

```bash
php artisan vendor:publish --tag="leaflet-for-laravel-config"
```

## Documentation

Visit the **[full documentation site](https://arielmejia.dev/leaflet-for-laravel/)** for guides on:

- [Maps](https://arielmejia.dev/leaflet-for-laravel/maps) — center, zoom, dimensions, tile layers, macros
- [Markers](https://arielmejia.dev/leaflet-for-laravel/markers) — popups, tooltips, icons, circles, bulk markers
- [Icons](https://arielmejia.dev/leaflet-for-laravel/icons) — colors, custom icons, Font Awesome & Material Design glyphs
- [Layer Control](https://arielmejia.dev/leaflet-for-laravel/layer-control) — base map switching, overlay groups
- [GeoJSON](https://arielmejia.dev/leaflet-for-laravel/geojson) — points, lines, files, APIs
- [Advanced Example](https://arielmejia.dev/leaflet-for-laravel/advanced-example) — full controller with everything combined
- [Artisan Command](https://arielmejia.dev/leaflet-for-laravel/artisan-command) — generate PHP or Livewire map classes

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [ArielMejiaDev](https://github.com/arielmejiadev)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
