<?php

use arielmejiadev\LeafletForLaravel\LeafletMap;
use Illuminate\Support\Facades\Blade;

it('renders @leafletStyles directive', function () {
    $html = Blade::render('@leafletStyles');

    expect($html)
        ->toContain('leaflet.css')
        ->toContain('leaflet.js')
        ->toContain('unpkg.com/leaflet@');
});

it('renders @leafletMap directive', function () {
    $map = LeafletMap::of('test')->center(51.505, -0.09)->zoom(13);

    $html = Blade::render('@leafletMap($map)', ['map' => $map]);

    expect($html)
        ->toContain('<div id="leaflet-test"')
        ->toContain('L.map(')
        ->toContain('setView([51.505, -0.09], 13)');
});

it('renders multiple maps independently', function () {
    $mapA = LeafletMap::of('alpha')->center(51.5, -0.09)->zoom(10);
    $mapB = LeafletMap::of('beta')->center(40.7, -74.0)->zoom(12);

    $html = Blade::render(
        '@leafletMap($a) @leafletMap($b)',
        ['a' => $mapA, 'b' => $mapB],
    );

    expect($html)
        ->toContain('leaflet-alpha')
        ->toContain('leaflet-beta')
        ->toContain('map_alpha')
        ->toContain('map_beta');
});

it('uses leaflet version from config', function () {
    config()->set('leaflet-for-laravel.leaflet_version', '1.8.0');

    $html = Blade::render('@leafletStyles');

    expect($html)->toContain('leaflet@1.8.0');
});
