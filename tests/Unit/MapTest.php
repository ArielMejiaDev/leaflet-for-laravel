<?php

use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Map;
use arielmejiadev\LeafletForLaravel\Marker;

enum BackedMapId: string
{
    case Headquarters = 'headquarters';
    case StoreLocator = 'store-locator';
}

enum UnitMapId
{
    case Dashboard;
    case Sidebar;
}

it('creates a map instance with of()', function () {
    $map = LeafletMap::of('my-map');

    expect($map)
        ->toBeInstanceOf(Map::class)
        ->getId()->toBe('my-map')
        ->getContainerId()->toBe('leaflet-my-map');
});

it('creates a map from a backed enum', function () {
    $map = LeafletMap::of(BackedMapId::Headquarters);

    expect($map)
        ->toBeInstanceOf(Map::class)
        ->getId()->toBe('headquarters')
        ->getContainerId()->toBe('leaflet-headquarters');
});

it('creates a map from a unit enum', function () {
    $map = LeafletMap::of(UnitMapId::Dashboard);

    expect($map)
        ->toBeInstanceOf(Map::class)
        ->getId()->toBe('Dashboard')
        ->getContainerId()->toBe('leaflet-Dashboard');
});

it('creates separate instances for different ids', function () {
    $mapA = LeafletMap::of('map-a')->center(51.505, -0.09)->zoom(13);
    $mapB = LeafletMap::of('map-b')->center(40.7128, -74.0060)->zoom(10);

    expect($mapA->getId())->toBe('map-a');
    expect($mapB->getId())->toBe('map-b');
    expect($mapA->toArray()['center'])->toBe(['lat' => 51.505, 'lng' => -0.09]);
    expect($mapB->toArray()['center'])->toBe(['lat' => 40.7128, 'lng' => -74.0060]);
});

it('sets center and zoom', function () {
    $data = LeafletMap::of('test')
        ->center(51.505, -0.09)
        ->zoom(15)
        ->toArray();

    expect($data['center'])->toBe(['lat' => 51.505, 'lng' => -0.09]);
    expect($data['zoom'])->toBe(15);
});

it('sets dimensions', function () {
    $map = LeafletMap::of('test')
        ->width('800px')
        ->height('600px');

    expect($map->getWidth())->toBe('800px');
    expect($map->getHeight())->toBe('600px');
});

it('sets tile layer configuration', function () {
    $data = LeafletMap::of('test')
        ->tileLayer('https://custom.tile/{z}/{x}/{y}.png', 17, 'Custom Attribution')
        ->toArray();

    expect($data['tileLayer'])->toBe('https://custom.tile/{z}/{x}/{y}.png');
    expect($data['maxZoom'])->toBe(17);
    expect($data['attribution'])->toBe('Custom Attribution');
});

it('sets max zoom independently', function () {
    $data = LeafletMap::of('test')->maxZoom(20)->toArray();

    expect($data['maxZoom'])->toBe(20);
});

it('sets scroll wheel zoom', function () {
    $data = LeafletMap::of('test')->scrollWheelZoom(false)->toArray();

    expect($data['scrollWheelZoom'])->toBeFalse();
});

it('adds a marker with popup string', function () {
    $data = LeafletMap::of('test')
        ->marker(51.505, -0.09, 'Hello World!')
        ->toArray();

    expect($data['markers'])->toHaveCount(1);
    expect($data['markers'][0]['lat'])->toBe(51.505);
    expect($data['markers'][0]['lng'])->toBe(-0.09);
    expect($data['markers'][0]['popup'])->toBe('Hello World!');
});

it('adds a marker with closure callback', function () {
    $data = LeafletMap::of('test')
        ->marker(51.505, -0.09, fn (Marker $pin) => $pin
            ->popup('<b>HQ</b>')
            ->tooltip('Click me')
            ->icon('blue')
            ->draggable()
            ->circle(200, 'blue', '#3388ff', 0.15)
        )
        ->toArray();

    $marker = $data['markers'][0];

    expect($marker['popup'])->toBe('<b>HQ</b>');
    expect($marker['tooltip'])->toBe('Click me');
    expect($marker['icon'])->toBeArray()->toHaveKey('iconUrl');
    expect($marker['draggable'])->toBeTrue();
    expect($marker['circle'])->toBeArray();
    expect($marker['circle']['radius'])->toBe(200);
});

it('adds markers from array', function () {
    $data = LeafletMap::of('test')
        ->markers([
            [40.826, -96.727, 'Lincoln, NE', 'blue'],
            [41.95, -97.226, 'Stanton, NE', 'green'],
        ])
        ->toArray();

    expect($data['markers'])->toHaveCount(2);
    expect($data['markers'][0]['popup'])->toBe('Lincoln, NE');
    expect($data['markers'][1]['popup'])->toBe('Stanton, NE');
    expect($data['markers'][0]['icon'])->toBeArray();
    expect($data['markers'][1]['icon'])->toBeArray();
});

it('adds marker instances directly', function () {
    $data = LeafletMap::of('test')
        ->markers([
            LeafletMap::marker(51.5, -0.09)->popup('Test'),
        ])
        ->toArray();

    expect($data['markers'])->toHaveCount(1);
    expect($data['markers'][0]['popup'])->toBe('Test');
});

it('enables fitBounds with padding', function () {
    $data = LeafletMap::of('test')
        ->marker(51.5, -0.09)
        ->fitBounds(0.3)
        ->toArray();

    expect($data['fitBounds'])->toBeTrue();
    expect($data['boundsPadding'])->toBe(0.3);
});

it('enables locate user', function () {
    $data = LeafletMap::of('test')
        ->locateUser(18)
        ->toArray();

    expect($data['locateUser'])->toBeTrue();
});

it('chains fluently', function () {
    $map = LeafletMap::of('fluent')
        ->center(51.505, -0.09)
        ->zoom(13)
        ->width('100%')
        ->height('500px')
        ->maxZoom(18)
        ->attribution('Test')
        ->scrollWheelZoom(false)
        ->marker(51.5, -0.09, 'Hello')
        ->fitBounds();

    expect($map)->toBeInstanceOf(Map::class);

    $data = $map->toArray();
    expect($data['markers'])->toHaveCount(1);
    expect($data['scrollWheelZoom'])->toBeFalse();
    expect($data['fitBounds'])->toBeTrue();
});

it('supports conditionable', function () {
    $shouldAddMarker = true;

    $data = LeafletMap::of('test')
        ->center(51.5, -0.09)
        ->when($shouldAddMarker, fn (Map $map) => $map->marker(51.5, -0.09, 'Conditional'))
        ->toArray();

    expect($data['markers'])->toHaveCount(1);
});

it('generates valid HTML', function () {
    $html = LeafletMap::of('test')
        ->center(51.505, -0.09)
        ->zoom(13)
        ->toHtml();

    expect($html)
        ->toContain('<div id="leaflet-test"')
        ->toContain('<script>')
        ->toContain('L.map(')
        ->toContain('L.tileLayer(')
        ->toContain('setView([51.505, -0.09], 13)');
});

it('generates valid script for markers', function () {
    $script = LeafletMap::of('test')
        ->marker(51.5, -0.09, 'Hello')
        ->fitBounds()
        ->toScript();

    expect($script)
        ->toContain('L.marker([51.5, -0.09])')
        ->toContain('bindPopup(')
        ->toContain('fitBounds(');
});

it('generates valid script for colored icons', function () {
    $script = LeafletMap::of('test')
        ->marker(51.5, -0.09, fn (Marker $pin) => $pin->icon('red'))
        ->toScript();

    expect($script)
        ->toContain('L.icon(')
        ->toContain('marker-icon-2x-red.png');
});

it('renders to string via __toString', function () {
    $map = LeafletMap::of('test')->center(51.5, -0.09)->zoom(13);

    $string = (string) $map;

    expect($string)->toContain('<div id="leaflet-test"');
});

it('serializes to json', function () {
    $json = LeafletMap::of('test')
        ->center(51.5, -0.09)
        ->zoom(13)
        ->toJson();

    $decoded = json_decode($json, true);

    expect($decoded['id'])->toBe('test');
    expect($decoded['center'])->toBe(['lat' => 51.5, 'lng' => -0.09]);
});
