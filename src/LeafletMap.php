<?php

namespace arielmejiadev\LeafletForLaravel;

use arielmejiadev\LeafletForLaravel\Enums\GlyphEnum;

class LeafletMap
{
    public static function of(string|\UnitEnum $id): Map
    {
        $resolvedId = match (true) {
            $id instanceof \BackedEnum => (string) $id->value,
            $id instanceof \UnitEnum => $id->name,
            default => $id,
        };

        return new Map($resolvedId);
    }

    public static function marker(float $lat, float $lng): Marker
    {
        return new Marker($lat, $lng);
    }

    public static function icon(string $url): Icon
    {
        return new Icon($url);
    }

    public static function colorIcon(string $color): Icon
    {
        return Icon::color($color);
    }

    public static function glyphIcon(GlyphEnum $enum): GlyphIcon
    {
        return GlyphIcon::from($enum);
    }

    public static function styles(): string
    {
        $version = config('leaflet-for-laravel.leaflet_version', '1.9.4');

        return '<link rel="stylesheet" href="https://unpkg.com/leaflet@'.$version.'/dist/leaflet.css" crossorigin=""/>'."\n"
            .'<script src="https://unpkg.com/leaflet@'.$version.'/dist/leaflet.js" crossorigin=""></script>';
    }

    /**
     * Returns the Leaflet.Icon.Glyph plugin script tag and the CSS for the given icon font enum(s).
     *
     * @param  class-string<GlyphEnum>|array<class-string<GlyphEnum>>  $enumClasses
     */
    public static function glyphStyles(string|array $enumClasses = []): string
    {
        $html = '<script src="https://cdn.jsdelivr.net/gh/Leaflet/Leaflet.Icon.Glyph@gh-pages/Leaflet.Icon.Glyph.js"></script>';

        $enumClasses = is_array($enumClasses) ? $enumClasses : [$enumClasses];

        foreach ($enumClasses as $enumClass) {
            if (is_string($enumClass) && method_exists($enumClass, 'cssUrl')) {
                $html = '<link rel="stylesheet" href="'.$enumClass::cssUrl().'"/>'."\n".$html;
            }
        }

        return $html;
    }
}
