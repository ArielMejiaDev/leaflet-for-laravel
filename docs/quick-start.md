# Quick Start

Get a Leaflet map rendering in your Laravel app in under a minute.

## 1. Build the map in your controller

```php
use arielmejiadev\LeafletForLaravel\LeafletMap;

class LandmarkController extends Controller
{
    public function index()
    {
        $map = LeafletMap::of('landmarks')
            ->center(40.6892, -74.0445)
            ->zoom(15)
            ->marker(40.6892, -74.0445, 'Statue of Liberty');

        return view('landmarks.index', compact('map'));
    }
}
```

## 2. Render it in your Blade view

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

That's it. `@leafletStyles` loads the Leaflet CSS and JS from the CDN. `@leafletMap($map)` renders the container `<div>` and the initialization `<script>`.

::: info Using AI to generate maps?
If you have [Laravel Boost](https://github.com/arielmejiadev/laravel-boost) installed, run:

```bash
php artisan boost:install arielmejiadev/leaflet-for-laravel
```

This publishes AI guidelines and skills for this package into your project. AI assistants like Claude and ChatGPT will use them to generate accurate map code that follows the package API — no need to paste documentation into your prompts.
:::

## Multiple maps in the same view

Each `LeafletMap::of()` call creates an independent instance. Pass different IDs and they render side by side without conflict:

```php
// Controller
$chicago = LeafletMap::of('chicago')
    ->center(41.9484, -87.6553)
    ->zoom(15)
    ->marker(41.9484, -87.6553, 'Wrigley Field');

$miami = LeafletMap::of('miami')
    ->center(25.7617, -80.1918)
    ->zoom(15)
    ->width('400px')
    ->height('300px')
    ->marker(25.7617, -80.1918, 'Pérez Art Museum Miami');

return view('dashboard', compact('chicago', 'miami'));
```

```blade
<head>
    @leafletStyles
</head>
<body>
    <div class="grid grid-cols-2 gap-4">
        @leafletMap($chicago)
        @leafletMap($miami)
    </div>
</body>
```

## Using enums for map IDs

Define an enum to avoid typos and get IDE autocompletion:

```php
enum MapId: string
{
    case Chicago = 'chicago';
    case Denver = 'denver';
}

$map = LeafletMap::of(MapId::Chicago)
    ->center(41.8796, -87.6237)
    ->zoom(15);
```

Unit enums work too (the case name becomes the ID):

```php
enum MapId
{
    case Chicago;
    case Denver;
}

$map = LeafletMap::of(MapId::Chicago); // id = "Chicago"
```

## What you can build

Here's a live example with multiple marker categories, overlay groups, base layer switching, and circle overlays — all generated from PHP:

<LeafletDemo />

See the [Advanced Example](advanced-example.md) for the full PHP code that produces this map.

## Example repository

Want to see the package in action in a real Laravel app? Check out the example repository:

[leaflet-for-laravel-app-example](https://github.com/ArielMejiaDev/leaflet-for-laravel-app-example)

## Next steps

- [Markers](markers.md) - Popups, tooltips, colored icons, circles
- [Maps](maps.md) - All map configuration options
- [Layer Control](layer-control.md) - Base maps and overlay groups
- [Advanced Example](advanced-example.md) - Full controller with base layers, overlay groups, circles
