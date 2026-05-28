---
name: leaflet-for-laravel-development
description: Build and render Leaflet.js maps in Laravel using a fluent PHP API with markers, icons, glyph icons (Font Awesome, Material Design), layer control, GeoJSON, and Blade directives.
---

# Leaflet for Laravel Development

## When to use this skill

Use this skill when the user wants to:

- Add an interactive map to a Laravel Blade view
- Display markers, pins, or locations on a map
- Show a store locator, office map, or location-based feature
- Render GeoJSON data on a map
- Add layer control (base map switching, toggleable marker groups)
- Use icon fonts on markers (Font Awesome, Material Design Icons, or custom icon libraries)
- Build an advanced map with multiple base layers, categorized overlay groups, and circle overlays
- Create a Livewire component with a map
- Use the Leaflet.js library within Laravel

## Key concepts

- **`LeafletMap`** is the static entry point (like `Str`). Use `LeafletMap::of('id')` to create a `Map` instance.
- **`Map`** is the fluent builder (like `Stringable`). Every method returns `$this` for chaining.
- **`Marker`** configures pins: `popup()`, `tooltip()`, `icon()`, `draggable()`, `circle()`.
- **`Icon`** handles marker icons: `Icon::color('blue')`, `Icon::fromStorage('path')`.
- **`GlyphIcon`** configures icon-font glyph markers: `GlyphIcon::fontAwesome(FontAwesomeIcon::Store)`, `GlyphIcon::materialDesign(MaterialDesignIcon::Home)`.
- **`GlyphEnum`** interface — implement it to add any icon font library as a PHP enum.
- **`MarkerGroup`** collects markers for overlay groups in layer control.
- Each `LeafletMap::of('id')` call creates an independent instance. Multiple maps on one page use different IDs.

## Required imports

```php
use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Marker;
use arielmejiadev\LeafletForLaravel\Icon;
use arielmejiadev\LeafletForLaravel\GlyphIcon;
use arielmejiadev\LeafletForLaravel\Map;
use arielmejiadev\LeafletForLaravel\Enums\FontAwesomeIcon;
use arielmejiadev\LeafletForLaravel\Enums\MaterialDesignIcon;
use arielmejiadev\LeafletForLaravel\Enums\GlyphEnum;
```

## Step-by-step: Add a map to a page

### Step 1: Build the map in the controller

```php
use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Marker;

public function index()
{
    $map = LeafletMap::of('locations')
        ->center(40.826, -96.727)
        ->zoom(13)
        ->marker(40.826, -96.727, fn (Marker $pin) => $pin
            ->popup('<b>Headquarters</b><br>Lincoln, NE')
            ->icon('blue')
        )
        ->fitBounds();

    return view('pages.index', compact('map'));
}
```

### Step 2: Render in Blade

```blade
<head>
    @leafletStyles
</head>
<body>
    @leafletMap($map)
</body>
```

`@leafletStyles` goes in `<head>` (loads Leaflet CSS + JS). `@leafletMap($map)` goes in `<body>` wherever the map should appear.

## Map fluent API reference

```php
LeafletMap::of('id')                // Create a new Map instance (accepts string or enum)
    ->center(float $lat, float $lng)    // Set center coordinates
    ->zoom(int $level)                  // Set zoom (default: 13)
    ->width(string $css)                // Container width (default: '100%')
    ->height(string $css)               // Container height (default: '400px')
    ->tileLayer(string $url, ?int $maxZoom, ?string $attribution)
    ->maxZoom(int $maxZoom)
    ->attribution(string $text)
    ->scrollWheelZoom(bool $enabled)
    ->marker(float $lat, float $lng, string|Closure|null $popupOrCallback)
    ->markers(array $markers)           // Bulk: [[lat, lng, popup, icon], ...]
    ->fitBounds(float $padding = 0.2)   // Auto-fit viewport to all markers
    ->baseLayer(string $name, string $url, int $maxZoom = 19, ?string $attribution = null)
    ->overlayGroup(string $name, Closure|array $markersOrCallback)
    ->geoJson(array $featureCollection)
    ->locateUser(int $maxZoom = 16)     // Browser geolocation
    ->when($condition, $callback)       // Conditional chaining (Conditionable)
    ->toHtml()                          // Terminal: returns <div> + <script>
    ->toArray()                         // Terminal: returns config array
    ->toJson()                          // Terminal: returns JSON string
```

## Marker configuration

```php
->marker(40.826, -96.727, fn (Marker $pin) => $pin
    ->popup('<b>Title</b><br>Description')     // HTML popup on click
    ->tooltip('Hover text')                     // Text on hover
    ->icon('blue')                              // Color: blue|green|red|orange|violet|gold|yellow
    ->icon(Icon::fromStorage('pins/custom.png')->size(32, 48)->anchor(16, 48))
    ->draggable()                               // Allow drag
    ->circle(200, 'blue', '#3388ff', 0.15)      // Circle overlay (radius in meters)
    ->glyph(FontAwesomeIcon::Store)             // Glyph icon from enum
    ->glyph(GlyphIcon::fontAwesome(FontAwesomeIcon::Star)->color('gold')->markerColor('red'))
)
```

Shorthand: `->marker(40.826, -96.727, 'Simple popup text')`

Bulk: `->markers([[40.826, -96.727, 'Label', 'blue'], [41.95, -97.226, 'Label', 'green']])`

## Layer control pattern

```php
$map = LeafletMap::of('multi-layer')
    ->baseLayer('OpenStreetMap', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png')
    ->baseLayer('Topo', 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', 17)
    ->overlayGroup('Category A', fn ($group) => $group
        ->marker(40.826, -96.727, fn ($pin) => $pin->popup('Point 1')->icon('blue'))
        ->marker(41.95, -97.226, fn ($pin) => $pin->popup('Point 2')->icon('blue'))
    )
    ->overlayGroup('Category B', fn ($group) => $group
        ->marker(40.623, -95.118, fn ($pin) => $pin->popup('Point 3')->icon('green'))
    )
    ->fitBounds();
```

## Glyph icon pattern (icon fonts on markers)

Uses the Leaflet.Icon.Glyph plugin to render Font Awesome, Material Design, or custom icon font glyphs directly on map markers.

```php
use arielmejiadev\LeafletForLaravel\Enums\FontAwesomeIcon;
use arielmejiadev\LeafletForLaravel\Enums\MaterialDesignIcon;
use arielmejiadev\LeafletForLaravel\GlyphIcon;

// Simple — enum shorthand
->marker(40.826, -96.727, fn (Marker $pin) => $pin
    ->popup('Store')->glyph(FontAwesomeIcon::Store)
)

// With custom color
->marker(40.826, -96.727, fn (Marker $pin) => $pin
    ->glyph(MaterialDesignIcon::Coffee, 'gold')
)

// Advanced — full GlyphIcon config
->marker(40.826, -96.727, fn (Marker $pin) => $pin
    ->glyph(
        GlyphIcon::fontAwesome(FontAwesomeIcon::Heart)
            ->color('white')
            ->size('11px')
            ->glyphAnchor(0, -7)
            ->markerColor('red')
    )
)
```

### Marker background colors

By default all glyph markers use the same blue pin — the Leaflet.Icon.Glyph library renders glyphs on a PNG image, not CSS. Use `markerColor()` to swap the pin to a different color:

```php
use arielmejiadev\LeafletForLaravel\GlyphIcon;
use arielmejiadev\LeafletForLaravel\Enums\FontAwesomeIcon;

// Each category gets a different colored pin
->overlayGroup('Restaurants', fn ($group) => $group
    ->marker(40.826, -96.727, fn (Marker $pin) => $pin
        ->popup('<b>Downtown Bistro</b>')
        ->glyph(GlyphIcon::fontAwesome(FontAwesomeIcon::Utensils)->markerColor('red'))
    )
)
->overlayGroup('Coffee Shops', fn ($group) => $group
    ->marker(40.820, -96.715, fn (Marker $pin) => $pin
        ->popup('<b>Bean & Brew</b>')
        ->glyph(GlyphIcon::fontAwesome(FontAwesomeIcon::Coffee)->markerColor('orange'))
    )
)
->overlayGroup('Hotels', fn ($group) => $group
    ->marker(40.818, -96.710, fn (Marker $pin) => $pin
        ->popup('<b>Grand Hotel</b>')
        ->glyph(GlyphIcon::fontAwesome(FontAwesomeIcon::Building)->markerColor('violet'))
    )
)
```

Available `markerColor` values: `blue`, `green`, `red`, `orange`, `violet`, `gold`, `yellow`, `grey`, `black`.

### GlyphIcon API

```php
GlyphIcon::from(GlyphEnum $enum)               // Create from any GlyphEnum
GlyphIcon::fontAwesome(FontAwesomeIcon $icon)   // Shorthand for Font Awesome
GlyphIcon::materialDesign(MaterialDesignIcon $icon)  // Shorthand for Material Design
    ->color(string $glyphColor)                 // Glyph color (default: 'white')
    ->size(string $glyphSize)                   // CSS font-size (e.g. '11px')
    ->glyphAnchor(int $x, int $y)              // Offset from marker center
    ->markerColor(string $color)                 // Marker pin color: blue|green|red|orange|violet|gold|yellow|grey|black
```

### Built-in enum icons

**FontAwesomeIcon** (prefix `fas`): `Home`, `Star`, `Heart`, `User`, `Users`, `Globe`, `MapMarker`, `MapPin`, `Map`, `LocationArrow`, `Compass`, `Building`, `Store`, `ShoppingCart`, `Coffee`, `Utensils`, `Hospital`, `School`, `Church`, `Car`, `Bus`, `Train`, `Plane`, `Ship`, `Bicycle`, `Parking`, `GasPump`, `Tree`, `Mountain`, `Water`, `Flag`, `Bell`, `Bolt`, `Fire`, `Wifi`, `Phone`, `Envelope`, `Camera`, `Music`, `Info`, `ExclamationTriangle`

**MaterialDesignIcon** (prefix `mdi`): `Home`, `Star`, `Heart`, `Account`, `AccountGroup`, `Earth`, `MapMarker`, `MapMarkerRadius`, `Map`, `Crosshairs`, `Compass`, `OfficeBuilding`, `Store`, `Cart`, `Coffee`, `Silverware`, `Hospital`, `School`, `Church`, `Car`, `Bus`, `Train`, `Airplane`, `Ferry`, `Bike`, `Parking`, `GasStation`, `Tree`, `Mountain`, `Water`, `Flag`, `Bell`, `Flash`, `Fire`, `Wifi`, `Phone`, `Email`, `Camera`, `Music`, `Information`, `Alert`

### Custom glyph enum

Implement the `GlyphEnum` interface to support any icon font:

```php
use arielmejiadev\LeafletForLaravel\Enums\GlyphEnum;

enum BootstrapIcon: string implements GlyphEnum
{
    case House = 'house';
    case Star = 'star';

    public function prefix(): string { return 'bi'; }
    public function glyph(): string { return $this->value; }
    public static function cssUrl(): string
    {
        return 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css';
    }
}
```

### Blade setup for glyph icons

```blade
<head>
    @leafletStyles
    @leafletGlyphStyles(\arielmejiadev\LeafletForLaravel\Enums\FontAwesomeIcon::class)
</head>
```

For multiple libraries: `@leafletGlyphStyles([FontAwesomeIcon::class, MaterialDesignIcon::class])`

## GeoJSON pattern

```php
$map = LeafletMap::of('geojson')
    ->geoJson([
        'type' => 'FeatureCollection',
        'features' => [
            [
                'type' => 'Feature',
                'properties' => ['popup' => '<b>Name</b>', 'color' => '#3388ff'],
                'geometry' => ['type' => 'Point', 'coordinates' => [-96.727, 40.826]],
            ],
        ],
    ])
    ->fitBounds();
```

Feature properties: `popup` (HTML for bindPopup) and `color` (fill/stroke color).

## Multiple maps on one page

```php
$mapA = LeafletMap::of('map-a')->center(40.826, -96.727)->zoom(13);
$mapB = LeafletMap::of('map-b')->center(41.95, -97.226)->zoom(12)->width('400px')->height('300px');
```

```blade
@leafletStyles  {{-- only once in <head> --}}
@leafletMap($mapA)
@leafletMap($mapB)
```

## Enum IDs for type safety

```php
enum MapId: string
{
    case Headquarters = 'headquarters';
    case Warehouse = 'warehouse';
}

$map = LeafletMap::of(MapId::Headquarters)->center(40.826, -96.727)->zoom(15);
```

Unit enums also work — the case name becomes the ID.

## Artisan scaffolding

```bash
php artisan make:leaflet StoreLocator
```

Prompts for **PHP** (generates `app/Leaflet/StoreLocator.php` with a `build(): Map` method) or **Livewire** (generates component + view with `refreshMap()` for polling).

## Advanced example (full controller)

Combines base layer switching, categorized overlay groups, colored markers with circle overlays, and auto-fit bounds:

```php
use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Marker;

class MapsController extends Controller
{
    public function index(Request $request)
    {
        $map = LeafletMap::of('stores')
            ->center(40.826, -96.727)
            ->zoom(7)
            ->baseLayer('OpenStreetMap', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png')
            ->baseLayer('OpenTopoMap', 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', 17)
            ->baseLayer('Satellite', 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', 18)
            ->baseLayer('CartoDB Positron', 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', 19)
            ->baseLayer('CartoDB Dark Matter', 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', 19)
            ->overlayGroup('Headquarters', fn ($group) => $group
                ->marker(40.826, -96.727, fn (Marker $pin) => $pin->popup('<b>Lincoln HQ</b><br>Main office')->icon('blue')->circle(500, '#2563eb', '#2563eb', 0.15))
                ->marker(41.256, -95.934, fn (Marker $pin) => $pin->popup('<b>Omaha HQ</b><br>Regional office')->icon('blue')->circle(500, '#2563eb', '#2563eb', 0.15))
                ->marker(40.697, -99.081, fn (Marker $pin) => $pin->popup('<b>Kearney HQ</b><br>Central office')->icon('blue')->circle(500, '#2563eb', '#2563eb', 0.15))
            )
            ->overlayGroup('Warehouses', fn ($group) => $group
                ->marker(41.95, -97.226, fn (Marker $pin) => $pin->popup('<b>Stanton Warehouse</b>')->icon('green')->circle(500, '#10b981', '#10b981', 0.15))
                ->marker(40.923, -98.342, fn (Marker $pin) => $pin->popup('<b>Grand Island Warehouse</b>')->icon('green')->circle(500, '#10b981', '#10b981', 0.15))
                ->marker(41.135, -95.945, fn (Marker $pin) => $pin->popup('<b>Bellevue Warehouse</b>')->icon('green')->circle(500, '#10b981', '#10b981', 0.15))
            )
            ->overlayGroup('Retail Stores', fn ($group) => $group
                ->marker(40.693, -96.047, fn (Marker $pin) => $pin->popup('<b>Nebraska City Store</b>')->icon('red')->circle(500, '#ef4444', '#ef4444', 0.15))
                ->marker(41.437, -96.497, fn (Marker $pin) => $pin->popup('<b>Fremont Store</b>')->icon('red')->circle(500, '#ef4444', '#ef4444', 0.15))
                ->marker(42.028, -97.417, fn (Marker $pin) => $pin->popup('<b>Norfolk Store</b>')->icon('red')->circle(500, '#ef4444', '#ef4444', 0.15))
            )
            ->fitBounds();

        return view('maps.index', compact('map'));
    }
}
```

## Common patterns

### Store locator from database

```php
$locations = Store::all();

$map = LeafletMap::of('stores');

foreach ($locations as $store) {
    $map->marker($store->lat, $store->lng, fn ($pin) => $pin
        ->popup("<b>{$store->name}</b><br>{$store->address}")
        ->icon('blue')
    );
}

$map->fitBounds();
```

### Conditional markers

```php
$map = LeafletMap::of('dashboard')
    ->center(40.826, -96.727)
    ->when($user->isAdmin(), fn ($map) => $map
        ->marker(40.826, -96.727, 'Admin-only marker')
    );
```

### Livewire with polling

```php
// app/Livewire/FleetTracker.php
public function buildMap(): Map
{
    $vehicles = Vehicle::all();
    $map = LeafletMap::of('fleet');

    foreach ($vehicles as $v) {
        $map->marker($v->lat, $v->lng, fn ($pin) => $pin
            ->popup($v->name)
            ->icon($v->is_active ? 'green' : 'red')
        );
    }

    return $map->fitBounds();
}
```

```blade
{{-- resources/views/livewire/fleet-tracker.blade.php --}}
<div wire:poll.5s="refreshMap">
    {!! $mapHtml !!}
</div>
```

## Configuration

Published via `php artisan vendor:publish --tag="leaflet-for-laravel-config"`.

| Key | Default | Description |
|-----|---------|-------------|
| `leaflet_version` | `1.9.4` | Leaflet CDN version |
| `tile_layer` | OpenStreetMap URL | Default tile provider |
| `max_zoom` | `19` | Default max zoom |
| `zoom` | `13` | Default initial zoom |
| `attribution` | OpenStreetMap | Default attribution |
| `width` | `100%` | Default container width |
| `height` | `400px` | Default container height |

All defaults are overridable per-map via the fluent API.
