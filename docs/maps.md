# Maps

The `Map` class is the fluent builder at the heart of the package. Create instances via `LeafletMap::of()` and chain methods to configure every aspect of your map.

<SimpleMapDemo />

```php
use arielmejiadev\LeafletForLaravel\LeafletMap;

$map = LeafletMap::of('griffith')
    ->center(34.1184, -118.3004)
    ->zoom(16);
```

## Available methods

### `center(float $lat, float $lng)`

Set the initial center coordinates. If omitted and markers exist, the map auto-fits to the markers.

```php
->center(34.1184, -118.3004) // Griffith Observatory, Los Angeles
```

### `zoom(int $level)`

Set the initial zoom level. Default: `13` (configurable).

```php
->zoom(15)
```

### `width(string $width)` / `height(string $height)`

Set the container dimensions. Accepts any CSS value. Defaults: `100%` width, `400px` height.

```php
->width('800px')
->height('600px')
```

### `tileLayer(string $url, ?int $maxZoom, ?string $attribution)`

Override the default tile layer. You can also set `maxZoom` and `attribution` independently.

```php
->tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', 17, 'OpenTopoMap')
```

### `maxZoom(int $maxZoom)`

```php
->maxZoom(18)
```

### `attribution(string $attribution)`

```php
->attribution('&copy; My Custom Attribution')
```

### `scrollWheelZoom(bool $enabled = true)`

Disable scroll-wheel zooming (useful for embedded maps):

```php
->scrollWheelZoom(false)
```

### `fitBounds(float $padding = 0.2)`

Automatically adjust the viewport to fit all markers. The padding adds breathing room around the edges.

```php
->marker(33.9534, -118.3390, 'SoFi Stadium')
->marker(34.0739, -118.2400, 'Walt Disney Concert Hall')
->fitBounds(0.3)
```

If no `center()` is set and markers exist, `fitBounds` is applied automatically.

> **Important:** `fitBounds()` overrides `zoom()`. It calculates the zoom level dynamically to fit all markers in the viewport. With a single marker, this results in a very close zoom. Use `fitBounds()` only when you have **multiple markers** spread across a region. For a single marker, use `center()` and `zoom()` instead:
> ```php
> // Single marker — use center + zoom
> LeafletMap::of('single')
>     ->center(40.6892, -74.0445)
>     ->zoom(15)
>     ->marker(40.6892, -74.0445, 'Statue of Liberty');
>
> // Multiple markers — fitBounds auto-frames them
> LeafletMap::of('multi')
>     ->marker(42.3467, -71.0972, 'Fenway Park')
>     ->marker(41.9484, -87.6553, 'Wrigley Field')
>     ->fitBounds();
> ```

### `locateUser(int $maxZoom = 16)`

Request the browser's geolocation API. Adds the user's position marker and an accuracy circle.

```php
->locateUser()
```

## Conditional chaining

The `Map` class uses Laravel's `Conditionable` trait, so you can branch mid-chain:

```php
$map = LeafletMap::of('conditional')
    ->center(37.8270, -122.4230) // Alcatraz Island, San Francisco
    ->when($user->isAdmin(), fn ($map) => $map
        ->marker(37.8270, -122.4230, 'Admin Dashboard')
    )
    ->unless($isMobile, fn ($map) => $map
        ->width('800px')
        ->height('600px')
    );
```

## Extending with macros

The `Map` class uses the `Macroable` trait. Register custom methods at boot time:

```php
// In a service provider
use arielmejiadev\LeafletForLaravel\Map;

Map::macro('fullscreen', function () {
    return $this->width('100vw')->height('100vh');
});

// Usage
$map = LeafletMap::of('hero')->fullscreen()->center(37.8199, -122.4783)->zoom(14); // Golden Gate Bridge
```

## Terminal methods

These methods end the fluent chain and return a value:

| Method | Returns | Description |
|---|---|---|
| `toHtml()` | `string` | The `<div>` + `<script>` HTML |
| `toScript()` | `string` | Just the JavaScript initialization code |
| `toArray()` | `array` | Full map configuration as an array |
| `toJson()` | `string` | JSON-encoded configuration |
| `__toString()` | `string` | Alias for `toHtml()` |

The `Map` class also implements `Htmlable`, `Jsonable`, `JsonSerializable`, `Arrayable`, and `Stringable`.
