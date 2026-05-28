<?php

use arielmejiadev\LeafletForLaravel\LeafletMap;

it('sets geojson data', function () {
    $geoData = [
        'type' => 'FeatureCollection',
        'features' => [
            [
                'type' => 'Feature',
                'properties' => ['popup' => 'Lincoln, NE'],
                'geometry' => ['type' => 'Point', 'coordinates' => [-96.727, 40.826]],
            ],
        ],
    ];

    $data = LeafletMap::of('test')
        ->geoJson($geoData)
        ->toArray();

    expect($data['geoJson'])->toBe($geoData);
});

it('generates geojson script', function () {
    $geoData = [
        'type' => 'FeatureCollection',
        'features' => [
            [
                'type' => 'Feature',
                'properties' => ['popup' => 'Test Point', 'color' => '#3388ff'],
                'geometry' => ['type' => 'Point', 'coordinates' => [-96.727, 40.826]],
            ],
        ],
    ];

    $script = LeafletMap::of('geo')
        ->geoJson($geoData)
        ->fitBounds()
        ->toScript();

    expect($script)
        ->toContain('L.geoJSON(')
        ->toContain('pointToLayer')
        ->toContain('onEachFeature')
        ->toContain('circleMarker')
        ->toContain('fitBounds(');
});

it('generates geojson with line features', function () {
    $geoData = [
        'type' => 'FeatureCollection',
        'features' => [
            [
                'type' => 'Feature',
                'properties' => ['color' => '#3388ff'],
                'geometry' => [
                    'type' => 'LineString',
                    'coordinates' => [[-96.727, 40.826], [-97.226, 41.95]],
                ],
            ],
        ],
    ];

    $script = LeafletMap::of('lines')
        ->geoJson($geoData)
        ->toScript();

    expect($script)
        ->toContain('L.geoJSON(')
        ->toContain('style:');
});
