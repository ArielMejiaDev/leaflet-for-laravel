# Blade Directives

The package provides two Blade directives for rendering maps in your views.

## `@leafletStyles`

Outputs the Leaflet CSS `<link>` and JS `<script>` tags from the unpkg CDN. Place this in your `<head>`:

```blade
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My App</title>
    @leafletStyles
</head>
```

This renders:

```html
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
```

The version is controlled by the `leaflet_version` config value.

## `@leafletMap($map)`

Renders a map instance. Outputs a container `<div>` with the map's dimensions and an inline `<script>` that initializes the Leaflet map:

```blade
<body>
    <h1>Our Locations</h1>
    @leafletMap($map)
</body>
```

Each map is wrapped in an IIFE (Immediately Invoked Function Expression), so multiple maps on the same page don't conflict.

## Full layout example

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Locator</title>
    @leafletStyles
</head>
<body>
    <header>
        <h1>Our Stores</h1>
    </header>

    <main>
        @leafletMap($map)
    </main>
</body>
</html>
```

## Multiple maps

Each `@leafletMap()` call is self-contained. You only need `@leafletStyles` once:

```blade
<head>
    @leafletStyles
</head>
<body>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h2>Main Office</h2>
            @leafletMap($mainMap)
        </div>
        <div>
            <h2>Branch Office</h2>
            @leafletMap($branchMap)
        </div>
    </div>
</body>
```

## Using `toHtml()` directly

Since the `Map` class implements `Htmlable`, you can also use it directly in Blade without the directive:

```blade
{{-- These are equivalent: --}}
@leafletMap($map)
{!! $map->toHtml() !!}
{!! $map !!}
```

## In a Blade component

```blade
{{-- resources/views/components/map-card.blade.php --}}
@props(['map', 'title'])

<div class="rounded-lg shadow p-4">
    <h3 class="text-lg font-bold mb-2">{{ $title }}</h3>
    @leafletMap($map)
</div>
```

```blade
<x-map-card :map="$headquarters" title="Headquarters" />
<x-map-card :map="$warehouse" title="Warehouse" />
```

## Embedding maps in an iframe

When a page has many elements (dashboards, forms, sidebars), embedding the map inside an `<iframe>` keeps it isolated from the rest of the page's CSS and JavaScript. No package changes are needed — just create a dedicated route that serves a minimal HTML page with only the map.

### 1. Create a standalone map route

```php
// routes/web.php
use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Marker;

Route::get('/map/embed/{id}', function (string $id) {
    $map = LeafletMap::of($id)
        ->center(40.7484, -73.9857)
        ->zoom(14)
        ->width('100%')
        ->height('100%')
        ->marker(40.7484, -73.9857, fn (Marker $pin) => $pin
            ->popup('<b>Empire State Building</b>')
            ->icon('blue')
        );

    return view('maps.embed', compact('map'));
})->name('map.embed');
```

### 2. Create the embed Blade view

This view renders a full HTML document with nothing but the map:

```blade
{{-- resources/views/maps/embed.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>html, body { margin: 0; padding: 0; width: 100%; height: 100%; }</style>
    @leafletStyles
</head>
<body>
    @leafletMap($map)
</body>
</html>
```

### 3. Use the iframe in any page

```blade
{{-- resources/views/dashboard.blade.php --}}
<div class="grid grid-cols-3 gap-4">
    <div class="col-span-2">
        <h2>Store Locator</h2>
        <iframe
            src="{{ route('map.embed', 'store-locator') }}"
            width="100%"
            height="500"
            style="border: none; border-radius: 8px;"
            loading="lazy"
        ></iframe>
    </div>

    <aside>
        <h2>Filters</h2>
        {{-- sidebar content --}}
    </aside>
</div>
```

### Passing dynamic data to the iframe

Use query parameters to make the embed configurable:

```php
// routes/web.php
Route::get('/map/embed/{id}', function (string $id, Request $request) {
    $lat = (float) $request->query('lat', 40.7484);
    $lng = (float) $request->query('lng', -73.9857);
    $zoom = (int) $request->query('zoom', 14);

    $map = LeafletMap::of($id)
        ->center($lat, $lng)
        ->zoom($zoom)
        ->width('100%')
        ->height('100%')
        ->marker($lat, $lng);

    return view('maps.embed', compact('map'));
})->name('map.embed');
```

```blade
<iframe
    src="{{ route('map.embed', ['id' => 'office', 'lat' => 41.8796, 'lng' => -87.6237, 'zoom' => 16]) }}"
    width="100%"
    height="400"
    style="border: none;"
    loading="lazy"
></iframe>
```

### Multiple iframes on the same page

Since each iframe loads its own document, you can embed as many maps as you need without conflicts:

```blade
<div class="grid grid-cols-2 gap-4">
    <div>
        <h3>New York</h3>
        <iframe src="{{ route('map.embed', 'nyc') }}" width="100%" height="300" style="border: none;" loading="lazy"></iframe>
    </div>
    <div>
        <h3>Los Angeles</h3>
        <iframe src="{{ route('map.embed', 'la') }}" width="100%" height="300" style="border: none;" loading="lazy"></iframe>
    </div>
</div>
```

> **Tip:** Use `loading="lazy"` on iframes so the map only loads when the user scrolls it into view. This improves page performance when embedding multiple maps.
