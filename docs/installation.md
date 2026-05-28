# Installation

## Requirements

- PHP 8.4+
- Laravel 11, 12, or 13

## Install via Composer

```bash
composer require arielmejiadev/leaflet-for-laravel
```

The package auto-discovers its service provider and facade. No manual registration is needed.

## Publish the config file

```bash
php artisan vendor:publish --tag="leaflet-for-laravel-config"
```

This publishes `config/leaflet-for-laravel.php` where you can set default values for all your maps:

```php
return [
    'leaflet_version' => '1.9.4',
    'tile_layer'      => 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
    'max_zoom'        => 19,
    'zoom'            => 13,
    'attribution'     => '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    'width'           => '100%',
    'height'          => '400px',
];
```

Every value here acts as a default for new `Map` instances. You can override any of them per-map using the fluent API.

## Publish views (optional)

```bash
php artisan vendor:publish --tag="leaflet-for-laravel-views"
```

## Next steps

Head to the [Quick Start](quick-start.md) to render your first map.
