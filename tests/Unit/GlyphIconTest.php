<?php

use arielmejiadev\LeafletForLaravel\Enums\FontAwesomeIcon;
use arielmejiadev\LeafletForLaravel\Enums\MaterialDesignIcon;
use arielmejiadev\LeafletForLaravel\GlyphIcon;
use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Map;
use arielmejiadev\LeafletForLaravel\Marker;

beforeEach(function () {
    Map::resetGlyphResources();
});

it('creates a glyph icon from a font awesome enum', function () {
    $icon = GlyphIcon::fontAwesome(FontAwesomeIcon::Globe);

    expect($icon->toArray())->toBe([
        'prefix' => 'fas',
        'glyph' => 'globe',
        'glyphColor' => 'white',
        'glyphSize' => '12px',
    ]);
});

it('creates a glyph icon from a material design enum', function () {
    $icon = GlyphIcon::materialDesign(MaterialDesignIcon::Home);

    expect($icon->toArray())->toBe([
        'prefix' => 'mdi',
        'glyph' => 'home',
        'glyphColor' => 'white',
        'glyphSize' => '20px',
    ]);
});

it('customizes glyph color and size', function () {
    $icon = GlyphIcon::from(FontAwesomeIcon::Star)
        ->color('gold')
        ->size('14px')
        ->glyphAnchor(0, -7);

    $data = $icon->toArray();

    expect($data['glyphColor'])->toBe('gold');
    expect($data['glyphSize'])->toBe('14px');
    expect($data['glyphAnchor'])->toBe([0, -7]);
});

it('sets marker color', function () {
    $icon = GlyphIcon::from(FontAwesomeIcon::Heart)->markerColor('red');

    expect($icon->toArray()['markerColor'])->toBe('red');
});

it('generates iconUrl from marker color in script', function () {
    $script = LeafletMap::of('glyph-colored')
        ->marker(40.826, -96.727, fn (Marker $pin) => $pin
            ->glyph(
                GlyphIcon::fontAwesome(FontAwesomeIcon::Store)->markerColor('green')
            )
        )
        ->toScript();

    expect($script)
        ->toContain('iconUrl:')
        ->toContain('data:image')
        ->toContain('svg+xml;base64,');
});

it('adds glyph to marker via enum shorthand', function () {
    $marker = new Marker(40.826, -96.727);
    $marker->glyph(FontAwesomeIcon::Store, 'yellow');

    $data = $marker->toArray();

    expect($data['glyphIcon'])->toBe([
        'prefix' => 'fas',
        'glyph' => 'store',
        'glyphColor' => 'yellow',
        'glyphSize' => '12px',
    ]);
});

it('adds glyph to marker via GlyphIcon instance', function () {
    $marker = new Marker(40.826, -96.727);
    $marker->glyph(
        GlyphIcon::fontAwesome(FontAwesomeIcon::Coffee)->size('11px')
    );

    $data = $marker->toArray();

    expect($data['glyphIcon']['glyph'])->toBe('coffee');
    expect($data['glyphIcon']['glyphSize'])->toBe('11px');
});

it('generates L.icon.glyph script for marker', function () {
    $script = LeafletMap::of('glyph-test')
        ->marker(40.826, -96.727, fn (Marker $pin) => $pin
            ->popup('Lincoln')
            ->glyph(FontAwesomeIcon::MapMarker)
        )
        ->toScript();

    expect($script)
        ->toContain('L.icon.glyph(')
        ->toContain('"fas"')
        ->toContain('"map-marker-alt"')
        ->toContain('"white"');
});

it('generates glyph icon with custom options in script', function () {
    $script = LeafletMap::of('glyph-custom')
        ->marker(40.826, -96.727, fn (Marker $pin) => $pin
            ->glyph(
                GlyphIcon::materialDesign(MaterialDesignIcon::Store)
                    ->color('gold')
                    ->size('12px')
                    ->glyphAnchor(0, -7)
                    ->markerColor('green')
            )
        )
        ->toScript();

    expect($script)
        ->toContain('"mdi"')
        ->toContain('"store"')
        ->toContain('"gold"')
        ->toContain('"12px"')
        ->toContain('glyphAnchor: [0, -7]')
        ->toContain('data:image')
        ->toContain('svg+xml;base64,');
});

it('uses glyph icons in overlay groups', function () {
    $script = LeafletMap::of('glyph-overlay')
        ->baseLayer('OSM', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png')
        ->overlayGroup('Stores', fn ($group) => $group
            ->marker(40.826, -96.727, fn (Marker $pin) => $pin
                ->popup('Lincoln')
                ->glyph(FontAwesomeIcon::Store)
            )
        )
        ->toScript();

    expect($script)
        ->toContain('L.icon.glyph(')
        ->toContain('L.layerGroup(')
        ->toContain('L.control.layers(');
});

it('returns glyph styles with plugin script', function () {
    $html = LeafletMap::glyphStyles();

    expect($html)->toContain('Leaflet.Icon.Glyph.js');
});

it('returns glyph styles with font awesome css', function () {
    $html = LeafletMap::glyphStyles(FontAwesomeIcon::class);

    expect($html)
        ->toContain('font-awesome')
        ->toContain('Leaflet.Icon.Glyph.js');
});

it('returns glyph styles with multiple icon font css', function () {
    $html = LeafletMap::glyphStyles([FontAwesomeIcon::class, MaterialDesignIcon::class]);

    expect($html)
        ->toContain('font-awesome')
        ->toContain('materialdesignicons')
        ->toContain('Leaflet.Icon.Glyph.js');
});

it('provides enum css urls', function () {
    expect(FontAwesomeIcon::cssUrl())->toContain('font-awesome');
    expect(MaterialDesignIcon::cssUrl())->toContain('materialdesignicons');
});

it('provides correct prefix for each library', function () {
    expect(FontAwesomeIcon::Globe->prefix())->toBe('fas');
    expect(MaterialDesignIcon::Earth->prefix())->toBe('mdi');
});

it('creates glyph icon from LeafletMap static helper', function () {
    $icon = LeafletMap::glyphIcon(FontAwesomeIcon::Star);

    expect($icon->toArray()['glyph'])->toBe('star');
});

it('auto-includes font awesome css in toHtml when glyph markers are used', function () {
    $html = LeafletMap::of('auto-fa')
        ->marker(40.826, -96.727, fn (Marker $pin) => $pin
            ->glyph(FontAwesomeIcon::Store)
        )
        ->toHtml();

    expect($html)
        ->toContain('font-awesome')
        ->toContain('Leaflet.Icon.Glyph.js');
});

it('auto-includes both fa and mdi css when mixing glyph libraries', function () {
    $html = LeafletMap::of('auto-mix')
        ->marker(40.826, -96.727, fn (Marker $pin) => $pin
            ->glyph(FontAwesomeIcon::Store)
        )
        ->marker(41.0, -96.5, fn (Marker $pin) => $pin
            ->glyph(MaterialDesignIcon::Water)
        )
        ->toHtml();

    expect($html)
        ->toContain('font-awesome')
        ->toContain('materialdesignicons')
        ->toContain('Leaflet.Icon.Glyph.js');
});

it('auto-includes glyph css for overlay group markers', function () {
    $html = LeafletMap::of('auto-overlay')
        ->baseLayer('OSM', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png')
        ->overlayGroup('Stores', fn ($group) => $group
            ->marker(40.826, -96.727, fn (Marker $pin) => $pin
                ->glyph(MaterialDesignIcon::Store)
            )
        )
        ->toHtml();

    expect($html)
        ->toContain('materialdesignicons')
        ->toContain('Leaflet.Icon.Glyph.js');
});

it('does not include glyph resources when no glyph markers are used', function () {
    $html = LeafletMap::of('no-glyph')
        ->marker(40.826, -96.727, 'Just a popup')
        ->toHtml();

    expect($html)
        ->not->toContain('Leaflet.Icon.Glyph.js')
        ->not->toContain('font-awesome');
});

it('deduplicates glyph resources across multiple maps', function () {
    $html1 = LeafletMap::of('map1')
        ->marker(40.826, -96.727, fn (Marker $pin) => $pin->glyph(FontAwesomeIcon::Store))
        ->toHtml();

    $html2 = LeafletMap::of('map2')
        ->marker(41.0, -96.5, fn (Marker $pin) => $pin->glyph(FontAwesomeIcon::Coffee))
        ->toHtml();

    expect($html1)->toContain('font-awesome');
    expect($html1)->toContain('Leaflet.Icon.Glyph.js');
    expect($html2)->not->toContain('font-awesome');
    expect($html2)->not->toContain('Leaflet.Icon.Glyph.js');
});
