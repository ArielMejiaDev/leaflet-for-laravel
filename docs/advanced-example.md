# Advanced Example

A full controller example combining base layer switching, categorized overlay groups, colored markers with circle overlays, and auto-fit bounds — showcasing iconic NYC landmarks.

<LeafletDemo />

## Controller

```php
<?php

namespace App\Http\Controllers;

use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Marker;
use Illuminate\Http\Request;

class MapsController extends Controller
{
    public function index(Request $request)
    {
        $map = LeafletMap::of('nyc-explorer')
            ->center(40.7580, -73.9855)
            ->zoom(12)
            ->baseLayer('Street', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png')
            ->baseLayer('Terrain', 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', 17)
            ->baseLayer('Satellite', 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', 18)
            ->baseLayer('Light', 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', 19)
            ->baseLayer('Dark', 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', 19)
            ->overlayGroup('Museums', fn ($group) => $group
                ->marker(40.7794, -73.9632, fn (Marker $pin) => $pin->popup('<b>The Met</b><br>1000 Fifth Ave')->icon('blue')->circle(500, '#2563eb', '#2563eb', 0.15))
                ->marker(40.7614, -73.9776, fn (Marker $pin) => $pin->popup('<b>MoMA</b><br>11 W 53rd St')->icon('blue')->circle(500, '#2563eb', '#2563eb', 0.15))
                ->marker(40.7830, -73.9590, fn (Marker $pin) => $pin->popup('<b>Guggenheim</b><br>1071 Fifth Ave')->icon('blue')->circle(500, '#2563eb', '#2563eb', 0.15))
            )
            ->overlayGroup('Stadiums', fn ($group) => $group
                ->marker(40.8296, -73.9262, fn (Marker $pin) => $pin->popup('<b>Yankee Stadium</b><br>1 E 161st St, Bronx')->icon('green')->circle(500, '#10b981', '#10b981', 0.15))
                ->marker(40.7505, -73.9934, fn (Marker $pin) => $pin->popup('<b>Madison Square Garden</b><br>4 Pennsylvania Plaza')->icon('green')->circle(500, '#10b981', '#10b981', 0.15))
                ->marker(40.7571, -73.8458, fn (Marker $pin) => $pin->popup('<b>Citi Field</b><br>41 Seaver Way, Queens')->icon('green')->circle(500, '#10b981', '#10b981', 0.15))
            )
            ->overlayGroup('Landmarks', fn ($group) => $group
                ->marker(40.6892, -74.0445, fn (Marker $pin) => $pin->popup('<b>Statue of Liberty</b><br>Liberty Island')->icon('red')->circle(500, '#ef4444', '#ef4444', 0.15))
                ->marker(40.7484, -73.9857, fn (Marker $pin) => $pin->popup('<b>Empire State Building</b><br>350 Fifth Ave')->icon('red')->circle(500, '#ef4444', '#ef4444', 0.15))
                ->marker(40.7061, -73.9969, fn (Marker $pin) => $pin->popup('<b>Brooklyn Bridge</b><br>Manhattan ↔ Brooklyn')->icon('red')->circle(500, '#ef4444', '#ef4444', 0.15))
            )
            ->fitBounds();

        return view('maps.index', compact('map'));
    }
}
```

## Blade view

```blade
<!DOCTYPE html>
<html>
<head>
    <title>NYC Explorer</title>
    @leafletStyles
</head>
<body>
    <h1>New York City</h1>
    @leafletMap($map)
</body>
</html>
```

## What this demonstrates

- **5 base layers** — users can switch between Street, Terrain, Satellite, Light, and Dark styles via radio buttons
- **3 overlay groups** — Museums (blue), Stadiums (green), Landmarks (red) toggled independently via checkboxes
- **Circle overlays** — each marker has a 500m radius circle matching its category color
- **Auto-fit bounds** — the viewport adjusts to show all markers regardless of which overlays are active
- **Full controller pattern** — ready to copy into any Laravel project

## Generate this with AI

You can use this prompt with Claude, ChatGPT, or any AI assistant to generate a map like the one above:

> Using the leaflet-for-laravel package, create a Laravel controller that renders an interactive map of New York City with 5 base layer options (Street, Terrain, Satellite, Light, Dark) and 3 toggleable overlay groups: Museums (The Met, MoMA, Guggenheim) with blue markers and 500m circles, Stadiums (Yankee Stadium, Madison Square Garden, Citi Field) with green markers and 500m circles, and Landmarks (Statue of Liberty, Empire State Building, Brooklyn Bridge) with red markers and 500m circles. Use fitBounds to auto-frame all markers. Include the Blade view with @leafletStyles and @leafletMap.

::: tip AI-friendly package
Because the API is fluent and self-descriptive, AI assistants can generate working maps from natural language prompts. Describe what you want — locations, categories, colors, base layers — and paste the result into your controller.
:::

Here are more prompt ideas to try:

**Store locator from a database**
> Create a controller that queries all Store records and renders a leaflet-for-laravel map with a marker for each store, using blue icons and HTML popups showing the store name and address. Use fitBounds to fit all markers.

**Delivery tracking with Livewire**
> Generate a Livewire component using leaflet-for-laravel that shows vehicle positions on a map with green markers for active vehicles and red for inactive. Use wire:poll to refresh every 5 seconds.

**GeoJSON data visualization**
> Build a controller that loads a GeoJSON file from storage and renders it on a leaflet-for-laravel map with colored point markers and popup labels from the feature properties.

**Multi-category city guide with glyph icons**
> Create a map with leaflet-for-laravel showing restaurants (red pin, utensils icon), coffee shops (orange pin, coffee icon), and hotels (violet pin, building icon) as overlay groups using Font Awesome glyph markers.
