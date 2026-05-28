# Leaflet for Laravel - Documentation

An expressive, fluent API for rendering [Leaflet.js](https://leafletjs.com) maps in Laravel applications using Blade views.

## Table of Contents

1. [Installation](installation.md) - Requirements, Composer install, publishing config
2. [Quick Start](quick-start.md) - Render your first map in under a minute
3. [Maps](maps.md) - Center, zoom, dimensions, tile layers, scroll wheel, macros
4. [Markers](markers.md) - Popups, tooltips, colored icons, circles, bulk markers
5. [Icons](icons.md) - Built-in colors, custom icons, Font Awesome & Material Design glyph icons
6. [Layer Control](layer-control.md) - Base map switching, toggleable overlay groups
7. [GeoJSON](geojson.md) - Points, lines, feature collections, loading from files/APIs
8. [Advanced Example](advanced-example.md) - Full controller with base layers, overlay groups, circles
10. [Blade Directives](blade-directives.md) - `@leafletStyles`, `@leafletMap`, multiple maps
11. [Artisan Command](artisan-command.md) - Generate PHP or Livewire map classes
12. [Configuration](configuration.md) - Default values, environment overrides

## Quick overview

```php
use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Marker;

// Build a map with a fluent API
$map = LeafletMap::of('nyc-landmarks')
    ->center(40.7484, -73.9857)
    ->zoom(13)
    ->marker(40.7484, -73.9857, fn (Marker $pin) => $pin
        ->popup('<b>Empire State Building</b><br>Manhattan, NY')
        ->icon('blue')
        ->circle(200)
    )
    ->marker(40.6892, -74.0445, 'Statue of Liberty')
    ->fitBounds();
```

```blade
{{-- In your Blade view --}}
<head>
    @leafletStyles
</head>
<body>
    @leafletMap($map)
</body>
```

## Architecture

The API follows the same pattern as Laravel's `Str` / `Stringable`:

| Class | Role | Like |
|---|---|---|
| `LeafletMap` | Static entry point with `::of()` factory | `Str` |
| `Map` | Fluent builder with chainable methods | `Stringable` |
| `Marker` | Marker configuration | - |
| `Icon` | Custom icon configuration | - |
| `GlyphIcon` | Icon font glyph configuration | - |
| `MarkerGroup` | Collection of markers for overlay groups | - |

Each `LeafletMap::of('id')` call creates an independent `Map` instance, so multiple maps can coexist in the same view.
