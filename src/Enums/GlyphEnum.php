<?php

namespace arielmejiadev\LeafletForLaravel\Enums;

interface GlyphEnum
{
    /** CSS class prefix (e.g. 'fas', 'mdi'). */
    public function prefix(): string;

    /** Glyph name without prefix (e.g. 'globe', 'home'). */
    public function glyph(): string;

    /** CDN URL for the icon font CSS. */
    public static function cssUrl(): string;
}
