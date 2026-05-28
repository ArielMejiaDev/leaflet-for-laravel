# Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag="leaflet-for-laravel-config"
```

This creates `config/leaflet-for-laravel.php`:

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

## Options

### `leaflet_version`

The Leaflet.js version loaded by `@leafletStyles`. Change this to upgrade or pin a specific version.

```php
'leaflet_version' => '1.9.4',
```

### `tile_layer`

The default tile layer URL template. Used when no `tileLayer()` or `baseLayer()` is specified.

```php
'tile_layer' => 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
```

Other popular options:

```php
// OpenStreetMap HOT
'tile_layer' => 'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png',

// OpenTopoMap
'tile_layer' => 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
```

### `max_zoom`

The default maximum zoom level. OpenStreetMap supports up to 19.

```php
'max_zoom' => 19,
```

### `zoom`

The default initial zoom level for new maps.

```php
'zoom' => 13,
```

### `attribution`

The default attribution text shown in the bottom-right corner of the map.

```php
'attribution' => '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
```

### `width` / `height`

Default container dimensions for new maps. Accepts any CSS value.

```php
'width'  => '100%',
'height' => '400px',
```

## Per-map overrides

Every config value can be overridden on a per-map basis using the fluent API:

```php
$map = LeafletMap::of('custom')
    ->tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', 17, 'OpenTopoMap')
    ->zoom(10)
    ->width('800px')
    ->height('600px');
```

## Environment-specific configuration

Use environment variables for different settings per environment:

```php
// config/leaflet-for-laravel.php
return [
    'leaflet_version' => env('LEAFLET_VERSION', '1.9.4'),
    'height'          => env('LEAFLET_DEFAULT_HEIGHT', '400px'),
];
```
