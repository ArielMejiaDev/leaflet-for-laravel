# GeoJSON

Render GeoJSON data directly on your map. The package supports `Point`, `LineString`, and other standard GeoJSON geometry types.

<GeoJsonDemo />

## Basic usage

Pass a GeoJSON array to the `geoJson()` method:

```php
$features = [
    'type' => 'FeatureCollection',
    'features' => [
        [
            'type' => 'Feature',
            'properties' => [
                'popup' => '<b>Golden Gate Bridge</b><br>San Francisco',
                'color' => '#3388ff',
            ],
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [-122.4783, 37.8199],  // [lng, lat] per GeoJSON spec
            ],
        ],
        [
            'type' => 'Feature',
            'properties' => [
                'popup' => '<b>Space Needle</b><br>Seattle',
                'color' => '#3388ff',
            ],
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [-122.3493, 47.6205],
            ],
        ],
    ],
];

$map = LeafletMap::of('west-coast')
    ->geoJson($features)
    ->fitBounds();
```

## Feature properties

The package recognizes two special properties on each feature:

| Property | Effect |
|---|---|
| `popup` | Binds a popup with the given HTML content |
| `color` | Sets the fill color for points or stroke color for lines |

```php
'properties' => [
    'popup' => '<b>Store Name</b><br>123 Main St',
    'color' => '#ff3333',
]
```

## Point features

Points are rendered as circle markers with a default style:

- Radius: `10`
- Fill color: `#3388ff` (overridden by `color` property)
- Stroke: white, weight 2
- Fill opacity: `0.85`

## Line features

LineString features connect coordinates with a styled line:

```php
$lines = [
    'type' => 'FeatureCollection',
    'features' => [
        [
            'type' => 'Feature',
            'properties' => ['color' => '#3388ff'],
            'geometry' => [
                'type' => 'LineString',
                'coordinates' => [
                    [-122.4783, 37.8199],  // Golden Gate Bridge
                    [-122.4194, 37.7749],  // San Francisco downtown
                    [-122.3493, 47.6205],  // Space Needle, Seattle
                ],
            ],
        ],
    ],
];
```

Lines use weight `3` and opacity `0.6` by default.

## Full example

Combining points and lines, similar to the `leaflet-geojson.html` example:

```php
$points = [
    'type' => 'FeatureCollection',
    'features' => [
        [
            'type' => 'Feature',
            'properties' => ['popup' => '<b>Statue of Liberty</b><br>New York', 'color' => '#3388ff'],
            'geometry' => ['type' => 'Point', 'coordinates' => [-74.0445, 40.6892]],
        ],
        [
            'type' => 'Feature',
            'properties' => ['popup' => '<b>Hollywood Sign</b><br>Los Angeles', 'color' => '#33cc66'],
            'geometry' => ['type' => 'Point', 'coordinates' => [-118.3215, 34.1341]],
        ],
        [
            'type' => 'Feature',
            'properties' => ['popup' => '<b>Gateway Arch</b><br>St. Louis', 'color' => '#ff3333'],
            'geometry' => ['type' => 'Point', 'coordinates' => [-90.1848, 38.6247]],
        ],
    ],
];

$map = LeafletMap::of('us-landmarks')
    ->geoJson($points)
    ->fitBounds(0.2);
```

## Loading GeoJSON from a file

```php
$geoJson = json_decode(file_get_contents(storage_path('geo/states.json')), true);

$map = LeafletMap::of('states')
    ->geoJson($geoJson)
    ->fitBounds();
```

## Loading GeoJSON from an API

```php
$geoJson = Http::get('https://api.example.com/locations.geojson')->json();

$map = LeafletMap::of('api-data')
    ->geoJson($geoJson)
    ->fitBounds();
```
