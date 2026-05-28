# Markers

Markers are the most common way to place points on a map. The package supports three ways to add them.

<MarkersDemo />

## Adding markers

### Simple popup string

```php
$map = LeafletMap::of('stadiums')
    ->marker(42.3467, -71.0972, 'Fenway Park, Boston')
    ->marker(40.7505, -73.9934, 'Madison Square Garden, NYC');
```

### Closure for rich configuration

```php
use arielmejiadev\LeafletForLaravel\Marker;

$map = LeafletMap::of('museum')
    ->marker(38.8882, -77.0199, fn (Marker $pin) => $pin
        ->popup('<b>Smithsonian National Air & Space Museum</b><br>Washington, DC')
        ->tooltip('Click for details')
        ->icon('blue')
        ->draggable()
        ->circle(200, 'blue', '#3388ff', 0.15)
    );
```

### Bulk markers from array

```php
$map = LeafletMap::of('us-stadiums')
    ->markers([
        [42.3467, -71.0972, 'Fenway Park, Boston', 'blue'],   // [lat, lng, popup, icon]
        [33.9534, -118.3390, 'SoFi Stadium, Los Angeles', 'green'],
        [44.5013, -88.0622, 'Lambeau Field, Green Bay', 'red'],
    ])
    ->fitBounds();
```

You can also pass `Marker` instances directly:

```php
$map = LeafletMap::of('dc-monuments')
    ->markers([
        LeafletMap::marker(38.8893, -77.0502)->popup('Lincoln Memorial')->icon('blue'),
        LeafletMap::marker(38.8895, -77.0353)->popup('Washington Monument')->icon('green'),
    ]);
```

## Marker methods

### `popup(string $content)`

HTML content shown when the marker is clicked. Since popups render HTML, you can include links, images, or any markup:

```php
->popup('<b>Store Name</b><br>123 Main St')
```

### `tooltip(string $content)`

Text shown on hover.

```php
->tooltip('Click for details')
```

### `icon(string|Icon $icon)`

Set the marker icon. Pass a color name for built-in colored markers:

```php
->icon('blue')    // Available: blue, green, red, orange, violet, gold, yellow
```

Or pass an `Icon` instance for full control (see [Icons](icons.md)):

```php
use arielmejiadev\LeafletForLaravel\Icon;

->icon(Icon::color('red')->size(32, 48))
```

### `draggable(bool $draggable = true)`

Allow the user to drag the marker.

```php
->draggable()
```

### `circle(int $radius, string $color, string $fillColor, float $fillOpacity)`

Draw a circle around the marker. Radius is in meters.

```php
->circle(200, 'blue', '#3388ff', 0.15)
```

All parameters have defaults, so you can call it with just a radius:

```php
->circle(500)
```

## Markers with links

Since `popup()` accepts HTML, you can add links that redirect users to external pages when they click a marker:

```php
use arielmejiadev\LeafletForLaravel\Marker;

$map = LeafletMap::of('nyc-links')
    ->marker(40.7794, -73.9632, fn (Marker $pin) => $pin
        ->popup('<b>The Metropolitan Museum of Art</b><br>1000 Fifth Ave<br><a href="https://www.metmuseum.org" target="_blank">Visit website</a>')
        ->icon('blue')
    )
    ->marker(40.7614, -73.9776, fn (Marker $pin) => $pin
        ->popup('<b>Museum of Modern Art</b><br>11 W 53rd St<br><a href="https://www.moma.org" target="_blank">Visit website</a>')
        ->icon('blue')
    )
    ->marker(40.7830, -73.9590, fn (Marker $pin) => $pin
        ->popup('<b>Guggenheim Museum</b><br>1071 Fifth Ave<br><a href="https://www.guggenheim.org" target="_blank">Visit website</a>')
        ->icon('blue')
    )
    ->marker(40.8296, -73.9262, fn (Marker $pin) => $pin
        ->popup('<b>Yankee Stadium</b><br>1 E 161st St, Bronx<br><a href="https://www.mlb.com/yankees/ballpark" target="_blank">Visit website</a>')
        ->icon('green')
    )
    ->marker(40.7505, -73.9934, fn (Marker $pin) => $pin
        ->popup('<b>Madison Square Garden</b><br>4 Pennsylvania Plaza<br><a href="https://www.msg.com" target="_blank">Visit website</a>')
        ->icon('green')
    )
    ->marker(40.6892, -74.0445, fn (Marker $pin) => $pin
        ->popup('<b>Statue of Liberty</b><br>Liberty Island<br><a href="https://www.nps.gov/stli" target="_blank">Visit website</a>')
        ->icon('red')
    )
    ->marker(40.7484, -73.9857, fn (Marker $pin) => $pin
        ->popup('<b>Empire State Building</b><br>350 Fifth Ave<br><a href="https://www.esbnyc.com" target="_blank">Visit website</a>')
        ->icon('red')
    )
    ->fitBounds();
```

> **Tip:** Use `target="_blank"` so links open in a new tab instead of navigating away from the map.

You can also style the link or add multiple actions:

```php
->popup('
    <b>Fenway Park</b><br>
    4 Jersey St, Boston<br>
    <a href="https://www.mlb.com/redsox/ballpark" target="_blank">Official site</a> |
    <a href="https://maps.google.com/?q=Fenway+Park" target="_blank">Directions</a>
')
```

## Full example

Colored markers with circles highlighting famous museums across the US:

```php
$map = LeafletMap::of('us-museums')
    ->marker(38.8913, -77.0200, fn (Marker $pin) => $pin
        ->popup('<b>Smithsonian</b><br>Washington, DC')
        ->icon('blue')
        ->circle(200, 'blue', '#3388ff', 0.15)
    )
    ->marker(34.0780, -118.4741, fn (Marker $pin) => $pin
        ->popup('<b>Getty Center</b><br>Los Angeles, CA')
        ->icon('blue')
        ->circle(200, 'blue', '#3388ff', 0.15)
    )
    ->marker(41.8796, -87.6237, fn (Marker $pin) => $pin
        ->popup('<b>Art Institute of Chicago</b><br>Chicago, IL')
        ->icon('green')
        ->circle(200, 'green', '#33ff88', 0.15)
    )
    ->marker(29.7256, -95.3907, fn (Marker $pin) => $pin
        ->popup('<b>Museum of Fine Arts</b><br>Houston, TX')
        ->icon('red')
        ->circle(200, 'red', '#ff3333', 0.15)
    )
    ->fitBounds(0.2);
```
