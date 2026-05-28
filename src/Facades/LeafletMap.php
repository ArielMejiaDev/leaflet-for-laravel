<?php

namespace arielmejiadev\LeafletForLaravel\Facades;

use arielmejiadev\LeafletForLaravel\Icon;
use arielmejiadev\LeafletForLaravel\Map;
use arielmejiadev\LeafletForLaravel\Marker;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Map of(string|\UnitEnum $id)
 * @method static Marker marker(float $lat, float $lng)
 * @method static Icon icon(string $url)
 * @method static Icon colorIcon(string $color)
 * @method static string styles()
 *
 * @see \arielmejiadev\LeafletForLaravel\LeafletMap
 */
class LeafletMap extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \arielmejiadev\LeafletForLaravel\LeafletMap::class;
    }
}
