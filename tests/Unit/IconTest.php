<?php

use arielmejiadev\LeafletForLaravel\Icon;

it('creates an icon from url', function () {
    $data = (new Icon('https://example.com/pin.png'))->toArray();

    expect($data['iconUrl'])->toBe('https://example.com/pin.png');
    expect($data['shadowUrl'])->toContain('marker-shadow.png');
    expect($data['iconSize'])->toBe([25, 41]);
    expect($data['iconAnchor'])->toBe([12, 41]);
    expect($data['popupAnchor'])->toBe([1, -34]);
});

it('creates a color icon', function () {
    $data = Icon::color('red')->toArray();

    expect($data['iconUrl'])->toContain('marker-icon-2x-red.png');
});

it('supports all built-in colors', function () {
    $colors = ['blue', 'green', 'red', 'orange', 'violet', 'gold', 'yellow'];

    foreach ($colors as $color) {
        $data = Icon::color($color)->toArray();
        expect($data['iconUrl'])->toContain("marker-icon-2x-{$color}.png");
    }
});

it('sets custom size', function () {
    $data = Icon::color('blue')->size(32, 48)->toArray();

    expect($data['iconSize'])->toBe([32, 48]);
});

it('sets custom anchor', function () {
    $data = Icon::color('blue')->anchor(16, 48)->toArray();

    expect($data['iconAnchor'])->toBe([16, 48]);
});

it('sets custom popup anchor', function () {
    $data = Icon::color('blue')->popupAnchor(0, -40)->toArray();

    expect($data['popupAnchor'])->toBe([0, -40]);
});

it('sets custom shadow', function () {
    $data = Icon::color('blue')
        ->shadow('https://example.com/shadow.png', 50, 50)
        ->toArray();

    expect($data['shadowUrl'])->toBe('https://example.com/shadow.png');
    expect($data['shadowSize'])->toBe([50, 50]);
});

it('sets shadow anchor', function () {
    $data = Icon::color('blue')->shadowAnchor(16, 41)->toArray();

    expect($data['shadowAnchor'])->toBe([16, 41]);
});

it('chains all methods', function () {
    $icon = Icon::color('blue')
        ->size(30, 45)
        ->anchor(15, 45)
        ->popupAnchor(0, -40)
        ->shadow('https://example.com/shadow.png', 45, 45)
        ->shadowAnchor(15, 45);

    expect($icon)->toBeInstanceOf(Icon::class);
});
