# Layer Control

Layer control lets users switch between base map styles (radio buttons) and toggle overlay groups on/off (checkboxes). This is useful for offering different map types (street, satellite, terrain) and for categorizing markers by state, color, category, or any grouping.

<LayerControlDemo />

## Base layers — multiple map types

Base layers let your users pick which map style they want to see. Leaflet renders them as **radio buttons** in a layer control panel — only one base layer is visible at a time. The first one added is selected by default.

```php
$map = LeafletMap::of('map')
    ->center(36.1069, -112.1129) // Grand Canyon
    ->zoom(10)
    ->baseLayer('OpenStreetMap', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png')
    ->baseLayer('OSM HOT', 'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png')
    ->baseLayer('OpenTopoMap', 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', 17)
    ->baseLayer('Esri Satellite', 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', 18)
    ->baseLayer('Esri Street', 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', 18)
    ->baseLayer('Esri Topo', 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', 18)
    ->baseLayer('CartoDB Positron', 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png')
    ->baseLayer('CartoDB Dark Matter', 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png')
    ->baseLayer('CartoDB Voyager', 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png')
    ->baseLayer('CyclOSM', 'https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png');
```

The layer control panel appears automatically in the top-right corner of the map whenever you define more than one base layer.

### Method signature

```php
->baseLayer(string $name, string $url, int $maxZoom = 19, ?string $attribution = null)
```

### Popular tile providers

| Name | URL template | Max zoom | Free |
|---|---|---|---|
| OpenStreetMap | `https://tile.openstreetmap.org/{z}/{x}/{y}.png` | 19 | Yes |
| OpenStreetMap HOT | `https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png` | 19 | Yes |
| OpenTopoMap | `https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png` | 17 | Yes |
| CyclOSM | `https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png` | 19 | Yes |
| Esri Satellite | `https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}` | 18 | Yes |
| Esri World Street Map | `https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}` | 18 | Yes |
| Esri World Topo Map | `https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}` | 18 | Yes |
| Light (CartoDB Positron) | `https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png` | 19 | Yes |
| Dark (CartoDB Dark Matter) | `https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png` | 19 | Yes |
| CartoDB Voyager | `https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png` | 19 | Yes |
| Mapbox Streets | `https://api.mapbox.com/styles/v1/mapbox/streets-v12/tiles/{z}/{x}/{y}?access_token=TOKEN` | 22 | API key |
| Thunderforest | `https://tile.thunderforest.com/outdoors/{z}/{x}/{y}.png?apikey=TOKEN` | 22 | API key |
| Stadia Alidade Smooth | `https://tiles.stadiamaps.com/tiles/alidade_smooth/{z}/{x}/{y}{r}.png` | 20 | API key |
| Stadia Alidade Dark | `https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png` | 20 | API key |
| Stadia Watercolor | `https://tiles.stadiamaps.com/tiles/stamen_watercolor/{z}/{x}/{y}.jpg` | 16 | API key |

> **Tip:** You can use any [Leaflet-compatible tile URL](https://leafletjs.com/reference.html#tilelayer). Providers marked **API key** require a free or paid token — just append it to the URL string.

## Overlay groups

Group markers into toggleable layers. Use a closure to build each group:

```php
use arielmejiadev\LeafletForLaravel\Marker;

$map = LeafletMap::of('nyc-attractions')
    ->baseLayer('Street', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png')
    ->baseLayer('Light', 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png')
    ->baseLayer('Dark', 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png')
    ->overlayGroup('Museums', fn ($group) => $group
        ->marker(40.7794, -73.9632, fn (Marker $pin) => $pin
            ->popup('<b>The Metropolitan Museum of Art</b><br>1000 Fifth Ave')
            ->icon('blue')
        )
        ->marker(40.7614, -73.9776, fn (Marker $pin) => $pin
            ->popup('<b>Museum of Modern Art (MoMA)</b><br>11 W 53rd St')
            ->icon('blue')
        )
    )
    ->overlayGroup('Stadiums', fn ($group) => $group
        ->marker(40.7505, -73.9934, fn (Marker $pin) => $pin
            ->popup('<b>Madison Square Garden</b><br>4 Pennsylvania Plaza')
            ->icon('green')
        )
    )
    ->overlayGroup('Landmarks', fn ($group) => $group
        ->marker(40.6892, -74.0445, fn (Marker $pin) => $pin
            ->popup('<b>Statue of Liberty</b><br>Liberty Island')
            ->icon('red')
        )
    )
    ->fitBounds();
```

You can also pass an array of `Marker` instances:

```php
->overlayGroup('DC Monuments', [
    LeafletMap::marker(38.8893, -77.0502)->popup('Lincoln Memorial')->icon('blue'),
    LeafletMap::marker(38.8895, -77.0353)->popup('Washington Monument')->icon('blue'),
])
```

## Combining base layers with overlay groups

Base layers and overlay groups work together in the same layer control panel. Base layers appear as radio buttons (pick one), overlay groups appear as checkboxes (toggle any combination):

```php
use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Marker;

$map = LeafletMap::of('sf-explorer')
    ->center(37.7749, -122.4194)
    ->zoom(12)

    // Map types — user picks one
    ->baseLayer('Street', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png')
    ->baseLayer('Satellite', 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', 18)
    ->baseLayer('Light', 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png')
    ->baseLayer('Dark', 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png')

    // Marker categories — user toggles each on/off
    ->overlayGroup('Museums', fn ($group) => $group
        ->marker(37.7714, -122.4686, fn (Marker $pin) => $pin
            ->popup('<b>de Young Museum</b>')
            ->icon('blue')
        )
        ->marker(37.7857, -122.4011, fn (Marker $pin) => $pin
            ->popup('<b>SFMOMA</b>')
            ->icon('blue')
        )
    )
    ->overlayGroup('Stadiums', fn ($group) => $group
        ->marker(37.7786, -122.3893, fn (Marker $pin) => $pin
            ->popup('<b>Oracle Park</b>')
            ->icon('green')
        )
    )
    ->fitBounds();
```

This gives your users full control: they can view museums on a satellite map, switch to the dark theme for stadiums, or any combination they prefer.

## Full example

See [Advanced Example](advanced-example.md) for a complete controller combining 5 base layers, 3 categorized overlay groups with colored markers and circle overlays.

## How it works

When you define base layers or overlay groups, the package automatically:

1. Creates `L.tileLayer` instances for each base layer
2. Creates `L.layerGroup` instances for each overlay group
3. Initializes the map with all layers visible
4. Adds an `L.control.layers` panel in the top-right corner for switching
5. Renders base layers as **radio buttons** (mutually exclusive)
6. Renders overlay groups as **checkboxes** (independently toggleable)
7. Fits the viewport to all markers when `fitBounds()` is called
