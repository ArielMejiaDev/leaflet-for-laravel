## Leaflet for Laravel

This package provides a fluent PHP API for rendering Leaflet.js maps in Laravel Blade views. Maps are built in PHP controllers and rendered with Blade directives — no JavaScript needed.

### Architecture

- `LeafletMap::of('id')` creates a new `Map` instance (like `Str::of()` creates a `Stringable`).
- Every method on `Map` returns `$this` for fluent chaining.
- Each `of()` call produces an independent instance — multiple maps can coexist on one page.
- The `Map` class uses `Conditionable`, `Tappable`, and `Macroable` traits.

### Quick Start

@verbatim
<code-snippet name="Build a map in a controller" lang="php">
use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Marker;

$map = LeafletMap::of('store-locator')
    ->center(40.826, -96.727)
    ->zoom(13)
    ->marker(40.826, -96.727, fn (Marker $pin) => $pin
        ->popup('<b>Headquarters</b><br>Lincoln, NE')
        ->icon('blue')
        ->circle(200)
    )
    ->fitBounds();

return view('stores.index', compact('map'));
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Render the map in Blade" lang="blade">
<head>
    @leafletStyles
</head>
<body>
    @leafletMap($map)
</body>
</code-snippet>
@endverbatim

### Fluent Map Methods

- `center(float $lat, float $lng)` — Set initial center coordinates.
- `zoom(int $level)` — Set zoom level (default: 13).
- `width(string $width)` / `height(string $height)` — Container dimensions (default: 100%, 400px).
- `tileLayer(string $url, ?int $maxZoom, ?string $attribution)` — Override default tile layer.
- `maxZoom(int $maxZoom)` — Set max zoom independently.
- `attribution(string $attribution)` — Set attribution text.
- `scrollWheelZoom(bool $enabled)` — Enable/disable scroll zooming.
- `fitBounds(float $padding = 0.2)` — Auto-fit viewport to all markers.
- `locateUser(int $maxZoom = 16)` — Request browser geolocation.

### Adding Markers

Three ways to add markers:

@verbatim
<code-snippet name="Marker with popup string" lang="php">
->marker(40.826, -96.727, 'Our office in Lincoln, NE')
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Marker with closure for rich config" lang="php">
use arielmejiadev\LeafletForLaravel\Marker;

->marker(40.826, -96.727, fn (Marker $pin) => $pin
    ->popup('<b>Office</b><br>Lincoln, NE')
    ->tooltip('Click for details')
    ->icon('blue')       // blue, green, red, orange, violet, gold, yellow
    ->draggable()
    ->circle(200, 'blue', '#3388ff', 0.15)
)
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Bulk markers from array" lang="php">
->markers([
    [40.826, -96.727, 'Lincoln, NE', 'blue'],   // [lat, lng, popup, icon]
    [41.95, -97.226, 'Stanton, NE', 'green'],
])
</code-snippet>
@endverbatim

### Custom Icons

@verbatim
<code-snippet name="Built-in color and custom storage icon" lang="php">
use arielmejiadev\LeafletForLaravel\Icon;

// Built-in color
->icon('red')

// From public/storage
->icon(Icon::fromStorage('pins/custom.png')->size(32, 48)->anchor(16, 48))

// From any URL
->icon(new Icon('https://example.com/pin.png'))
</code-snippet>
@endverbatim

### Layer Control

@verbatim
<code-snippet name="Base layers and overlay groups" lang="php">
$map = LeafletMap::of('controlled')
    ->baseLayer('OpenStreetMap', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png')
    ->baseLayer('OpenTopoMap', 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', 17)
    ->overlayGroup('Nebraska', fn ($group) => $group
        ->marker(40.826, -96.727, fn ($pin) => $pin->popup('Lincoln')->icon('blue'))
        ->marker(41.95, -97.226, fn ($pin) => $pin->popup('Stanton')->icon('blue'))
    )
    ->overlayGroup('Iowa', fn ($group) => $group
        ->marker(40.623, -95.118, fn ($pin) => $pin->popup('College Springs')->icon('green'))
    )
    ->fitBounds();
</code-snippet>
@endverbatim

### Glyph Icons (Icon Fonts)

Display icon-font glyphs (Font Awesome, Material Design Icons) directly on map markers using the Leaflet.Icon.Glyph plugin. Use the `glyph()` method on a marker instead of `icon()`.

@verbatim
<code-snippet name="Glyph markers with Font Awesome and Material Design" lang="php">
use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Marker;
use arielmejiadev\LeafletForLaravel\GlyphIcon;
use arielmejiadev\LeafletForLaravel\Enums\FontAwesomeIcon;
use arielmejiadev\LeafletForLaravel\Enums\MaterialDesignIcon;

$map = LeafletMap::of('glyph-demo')
    // Simple — pass an enum directly
    ->marker(40.826, -96.727, fn (Marker $pin) => $pin
        ->popup('Store')->glyph(FontAwesomeIcon::Store)
    )
    // With custom glyph color
    ->marker(41.95, -97.226, fn (Marker $pin) => $pin
        ->popup('Office')->glyph(MaterialDesignIcon::OfficeBuilding, 'gold')
    )
    // Advanced — full GlyphIcon config
    ->marker(40.623, -95.118, fn (Marker $pin) => $pin
        ->popup('Cafe')->glyph(
            GlyphIcon::fontAwesome(FontAwesomeIcon::Coffee)
                ->color('white')
                ->size('11px')
                ->glyphAnchor(0, -7)
                ->markerColor('red')
        )
    )
    ->fitBounds();
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Glyph styles in Blade" lang="blade">
<head>
    @leafletStyles
    {{-- Single library --}}
    @leafletGlyphStyles(\arielmejiadev\LeafletForLaravel\Enums\FontAwesomeIcon::class)

    {{-- Multiple libraries --}}
    @leafletGlyphStyles([
        \arielmejiadev\LeafletForLaravel\Enums\FontAwesomeIcon::class,
        \arielmejiadev\LeafletForLaravel\Enums\MaterialDesignIcon::class,
    ])
</head>
</code-snippet>
@endverbatim

By default all glyph markers share the same blue pin background (a library limitation — the Leaflet.Icon.Glyph plugin uses a PNG image, not CSS). Use `markerColor()` to swap the pin to a different color.

@verbatim
<code-snippet name="Glyph markers with different background colors per category" lang="php">
use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Marker;
use arielmejiadev\LeafletForLaravel\GlyphIcon;
use arielmejiadev\LeafletForLaravel\Enums\FontAwesomeIcon;

$map = LeafletMap::of('city-guide')
    ->baseLayer('OpenStreetMap', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png')
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
    ->fitBounds();
// Available markerColor values: blue, green, red, orange, violet, gold, yellow, grey, black
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Custom glyph enum for any icon font library" lang="php">
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

// Usage: ->glyph(BootstrapIcon::House)
// Blade: @leafletGlyphStyles(App\Enums\BootstrapIcon::class)
</code-snippet>
@endverbatim

### Advanced Example

A full controller combining base layer switching, categorized overlay groups with colored markers, circle overlays, and auto-fit bounds.

@verbatim
<code-snippet name="Advanced controller with base layers, overlay groups, circles" lang="php">
use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Marker;

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
</code-snippet>
@endverbatim

### GeoJSON

@verbatim
<code-snippet name="Render GeoJSON data" lang="php">
$geoJson = [
    'type' => 'FeatureCollection',
    'features' => [
        [
            'type' => 'Feature',
            'properties' => ['popup' => '<b>Lincoln</b>', 'color' => '#3388ff'],
            'geometry' => ['type' => 'Point', 'coordinates' => [-96.727, 40.826]],
        ],
    ],
];

$map = LeafletMap::of('geo')->geoJson($geoJson)->fitBounds();
</code-snippet>
@endverbatim

### Multiple Maps in One View

@verbatim
<code-snippet name="Two independent maps on the same page" lang="php">
$main = LeafletMap::of('main')->center(40.826, -96.727)->zoom(15);
$mini = LeafletMap::of('mini')->center(41.95, -97.226)->zoom(12)->width('300px')->height('200px');

// In Blade: @leafletMap($main) and @leafletMap($mini)
</code-snippet>
@endverbatim

### Enum IDs

@verbatim
<code-snippet name="Use enums for type-safe map IDs" lang="php">
enum MapId: string
{
    case Headquarters = 'headquarters';
    case Warehouse = 'warehouse';
}

$map = LeafletMap::of(MapId::Headquarters)->center(40.826, -96.727)->zoom(15);
</code-snippet>
@endverbatim

### Blade Directives

- `@leafletStyles` — Place in `<head>`. Outputs Leaflet CSS + JS from CDN.
- `@leafletGlyphStyles($enumClass)` — Place in `<head>` after `@leafletStyles`. Loads the Leaflet.Icon.Glyph plugin and the icon font CSS. Pass enum class(es) for the icon libraries used.
- `@leafletMap($map)` — Renders the map container `<div>` and initialization `<script>`.

### Artisan Command

Run `php artisan make:leaflet StoreLocator` to generate a PHP or Livewire map class. The command prompts for the type.

### Configuration

Publish with `php artisan vendor:publish --tag="leaflet-for-laravel-config"`. Keys: `leaflet_version`, `tile_layer`, `max_zoom`, `zoom`, `attribution`, `width`, `height`.
