<?php

use arielmejiadev\LeafletForLaravel\Icon;
use arielmejiadev\LeafletForLaravel\Marker;

it('creates a marker with coordinates', function () {
    $data = (new Marker(51.505, -0.09))->toArray();

    expect($data['lat'])->toBe(51.505);
    expect($data['lng'])->toBe(-0.09);
    expect($data['popup'])->toBeNull();
    expect($data['tooltip'])->toBeNull();
    expect($data['draggable'])->toBeFalse();
});

it('sets popup content', function () {
    $data = (new Marker(51.5, -0.09))
        ->popup('<b>Hello</b>')
        ->toArray();

    expect($data['popup'])->toBe('<b>Hello</b>');
});

it('sets tooltip content', function () {
    $data = (new Marker(51.5, -0.09))
        ->tooltip('Hover me')
        ->toArray();

    expect($data['tooltip'])->toBe('Hover me');
});

it('sets draggable', function () {
    $data = (new Marker(51.5, -0.09))
        ->draggable()
        ->toArray();

    expect($data['draggable'])->toBeTrue();
});

it('sets icon from color string', function () {
    $data = (new Marker(51.5, -0.09))
        ->icon('blue')
        ->toArray();

    expect($data['icon'])->toBeArray();
    expect($data['icon']['iconUrl'])->toContain('marker-icon-2x-blue.png');
});

it('sets icon from Icon instance', function () {
    $icon = Icon::color('green')->size(30, 50);

    $data = (new Marker(51.5, -0.09))
        ->icon($icon)
        ->toArray();

    expect($data['icon']['iconUrl'])->toContain('green.png');
    expect($data['icon']['iconSize'])->toBe([30, 50]);
});

it('adds a circle around the marker', function () {
    $data = (new Marker(51.5, -0.09))
        ->circle(300, 'red', '#ff3333', 0.2)
        ->toArray();

    expect($data['circle'])->toBeArray();
    expect($data['circle']['lat'])->toBe(51.5);
    expect($data['circle']['lng'])->toBe(-0.09);
    expect($data['circle']['radius'])->toBe(300);
    expect($data['circle']['color'])->toBe('red');
    expect($data['circle']['fillColor'])->toBe('#ff3333');
    expect($data['circle']['fillOpacity'])->toBe(0.2);
});

it('chains all methods fluently', function () {
    $marker = (new Marker(51.5, -0.09))
        ->popup('Hello')
        ->tooltip('World')
        ->icon('red')
        ->draggable()
        ->circle(200);

    expect($marker)->toBeInstanceOf(Marker::class);

    $data = $marker->toArray();
    expect($data['popup'])->toBe('Hello');
    expect($data['tooltip'])->toBe('World');
    expect($data['icon'])->toBeArray();
    expect($data['draggable'])->toBeTrue();
    expect($data['circle'])->toBeArray();
});
