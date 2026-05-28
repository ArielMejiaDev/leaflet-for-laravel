<?php

use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Marker;

it('adds base layers', function () {
    $data = LeafletMap::of('test')
        ->baseLayer('OpenStreetMap', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png')
        ->baseLayer('OpenTopoMap', 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', 17)
        ->toArray();

    expect($data['baseLayers'])->toHaveCount(2);
    expect($data['baseLayers'][0]['name'])->toBe('OpenStreetMap');
    expect($data['baseLayers'][1]['name'])->toBe('OpenTopoMap');
    expect($data['baseLayers'][1]['maxZoom'])->toBe(17);
});

it('adds overlay groups with closure', function () {
    $data = LeafletMap::of('test')
        ->overlayGroup('Nebraska (NE)', fn ($group) => $group
            ->marker(40.826, -96.727, 'Lincoln')
            ->marker(41.95, -97.226, 'Stanton')
        )
        ->overlayGroup('Iowa (IA)', fn ($group) => $group
            ->marker(40.623, -95.118, 'College Springs')
        )
        ->toArray();

    expect($data['overlayGroups'])->toHaveCount(2);
    expect($data['overlayGroups']['Nebraska (NE)'])->toHaveCount(2);
    expect($data['overlayGroups']['Iowa (IA)'])->toHaveCount(1);
});

it('adds overlay groups with marker array', function () {
    $data = LeafletMap::of('test')
        ->overlayGroup('Group A', [
            LeafletMap::marker(51.5, -0.09)->popup('Marker A'),
        ])
        ->toArray();

    expect($data['overlayGroups']['Group A'])->toHaveCount(1);
    expect($data['overlayGroups']['Group A'][0]['popup'])->toBe('Marker A');
});

it('detects layer control', function () {
    $withLayers = LeafletMap::of('with')
        ->baseLayer('OSM', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png');

    $withoutLayers = LeafletMap::of('without')
        ->center(51.5, -0.09);

    expect($withLayers->hasLayerControl())->toBeTrue();
    expect($withoutLayers->hasLayerControl())->toBeFalse();
});

it('generates layer control script', function () {
    $script = LeafletMap::of('test')
        ->baseLayer('OpenStreetMap', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png')
        ->baseLayer('Topo', 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', 17)
        ->overlayGroup('Nebraska', fn ($group) => $group
            ->marker(40.826, -96.727, fn (Marker $pin) => $pin->popup('Lincoln')->icon('blue'))
            ->marker(41.95, -97.226, fn (Marker $pin) => $pin->popup('Stanton')->icon('blue'))
        )
        ->overlayGroup('Iowa', fn ($group) => $group
            ->marker(40.623, -95.118, fn (Marker $pin) => $pin->popup('College Springs')->icon('green'))
        )
        ->fitBounds()
        ->toScript();

    expect($script)
        ->toContain('L.tileLayer(')
        ->toContain('L.layerGroup(')
        ->toContain('L.control.layers(')
        ->toContain('"OpenStreetMap"')
        ->toContain('"Nebraska"')
        ->toContain('"Iowa"')
        ->toContain('fitBounds(');
});

it('generates full layer control example matching leaflet-layers-control.html', function () {
    $map = LeafletMap::of('controlled')
        ->baseLayer('OpenStreetMap', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png')
        ->baseLayer('OpenStreetMap HOT', 'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png')
        ->baseLayer('OpenTopoMap', 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', 17)
        ->overlayGroup('Nebraska (NE)', fn ($group) => $group
            ->marker(40.826, -96.727, fn (Marker $pin) => $pin->popup('<b>4611 West Adams</b><br>Lincoln, NE')->icon('blue'))
            ->marker(41.95, -97.226, fn (Marker $pin) => $pin->popup('<b>84198 562nd Ave</b><br>Stanton, NE')->icon('blue'))
            ->marker(41.418, -99.134, fn (Marker $pin) => $pin->popup('<b>80025 463rd Ave</b><br>Arcadia, NE')->icon('blue'))
        )
        ->overlayGroup('Iowa (IA)', fn ($group) => $group
            ->marker(40.623, -95.118, fn (Marker $pin) => $pin->popup('<b>1003 Iowa Ave</b><br>College Springs, IA')->icon('green'))
            ->marker(42.978, -95.688, fn (Marker $pin) => $pin->popup('<b>4375 Pierce Ave</b><br>Paullina, IA')->icon('green'))
        )
        ->overlayGroup('New Mexico (NM)', fn ($group) => $group
            ->marker(35.1107, -106.5554, fn (Marker $pin) => $pin->popup('<b>4625 Wyoming Blvd NE</b><br>Albuquerque, NM')->icon('red'))
            ->marker(35.6672, -105.96, fn (Marker $pin) => $pin->popup('<b>1090 S St Francis Dr</b><br>Santa Fe, NM')->icon('red'))
        )
        ->fitBounds();

    $script = $map->toScript();

    // Should have 3 base layers
    expect($script)->toContain('"OpenStreetMap"');
    expect($script)->toContain('"OpenStreetMap HOT"');
    expect($script)->toContain('"OpenTopoMap"');

    // Should have 3 overlay groups
    expect($script)->toContain('"Nebraska (NE)"');
    expect($script)->toContain('"Iowa (IA)"');
    expect($script)->toContain('"New Mexico (NM)"');

    // Should have control and fitBounds
    expect($script)->toContain('L.control.layers(');
    expect($script)->toContain('fitBounds(');
});

it('includes circles in overlay layer groups instead of adding to map directly', function () {
    $script = LeafletMap::of('circles-overlay')
        ->baseLayer('OSM', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png')
        ->overlayGroup('Stores', fn ($group) => $group
            ->marker(40.826, -96.727, fn (Marker $pin) => $pin
                ->popup('Lincoln')
                ->icon('blue')
                ->circle(200, 'blue', '#3388ff', 0.15)
            )
        )
        ->fitBounds()
        ->toScript();

    // Circle should be a named variable, not added to map directly
    expect($script)->toContain('var om_0_0_circle = L.circle(');

    // Circle should be included in the layer group alongside the marker
    expect($script)->toContain('L.layerGroup([om_0_0, om_0_0_circle])');

    // No anonymous "L.circle(...).addTo(map)" — the old bug pattern
    expect($script)->not->toMatch('/L\.circle\([^;]+\.addTo\(/');
});

it('still adds circles directly to map for non-overlay markers', function () {
    $script = LeafletMap::of('circles-direct')
        ->marker(40.826, -96.727, fn (Marker $pin) => $pin
            ->popup('Lincoln')
            ->circle(200, 'blue', '#3388ff', 0.15)
        )
        ->fitBounds()
        ->toScript();

    // Circle should be added to the map directly (no named variable)
    expect($script)->toContain('L.circle([40.826, -96.727]');
    expect($script)->toContain('.addTo(map_circles_direct)');
});
