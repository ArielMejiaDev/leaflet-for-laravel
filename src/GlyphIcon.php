<?php

namespace arielmejiadev\LeafletForLaravel;

use arielmejiadev\LeafletForLaravel\Enums\FontAwesomeIcon;
use arielmejiadev\LeafletForLaravel\Enums\GlyphEnum;
use arielmejiadev\LeafletForLaravel\Enums\MaterialDesignIcon;
use Illuminate\Contracts\Support\Arrayable;

class GlyphIcon implements Arrayable
{
    protected string $prefix;

    protected string $glyph;

    protected string $glyphColor = 'white';

    protected ?string $glyphSize = '12px';

    protected ?array $glyphAnchor = null;

    protected ?string $markerColor = null;

    protected ?array $iconSize = null;

    protected ?array $iconAnchor = null;

    protected ?array $popupAnchor = null;

    protected ?string $cssUrl = null;

    public function __construct(GlyphEnum $enum)
    {
        $this->prefix = $enum->prefix();
        $this->glyph = $enum->glyph();

        if (method_exists($enum, 'cssUrl')) {
            $this->cssUrl = $enum::cssUrl();
        }

        if ($this->prefix === 'mdi') {
            $this->glyphSize = '20px';
        }
    }

    public static function from(GlyphEnum $enum): static
    {
        return new static($enum);
    }

    public static function fontAwesome(FontAwesomeIcon $icon): static
    {
        return new static($icon);
    }

    public static function materialDesign(MaterialDesignIcon $icon): static
    {
        return new static($icon);
    }

    public function color(string $glyphColor): static
    {
        $this->glyphColor = $glyphColor;

        return $this;
    }

    public function size(string $glyphSize): static
    {
        $this->glyphSize = $glyphSize;

        return $this;
    }

    public function glyphAnchor(int $x, int $y): static
    {
        $this->glyphAnchor = [$x, $y];

        return $this;
    }

    /**
     * Set the marker background color using colored marker PNGs.
     *
     * Available: blue, green, red, orange, violet, gold, yellow, grey, black.
     */
    public function markerColor(string $color): static
    {
        $this->markerColor = $color;

        return $this;
    }

    public function iconSize(int $width, int $height): static
    {
        $this->iconSize = [$width, $height];

        return $this;
    }

    public function iconAnchor(int $x, int $y): static
    {
        $this->iconAnchor = [$x, $y];

        return $this;
    }

    public function popupAnchor(int $x, int $y): static
    {
        $this->popupAnchor = [$x, $y];

        return $this;
    }

    public function getCssUrl(): ?string
    {
        return $this->cssUrl;
    }

    public function toArray(): array
    {
        $data = [
            'prefix' => $this->prefix,
            'glyph' => $this->glyph,
            'glyphColor' => $this->glyphColor,
        ];

        if ($this->glyphSize !== null) {
            $data['glyphSize'] = $this->glyphSize;
        }

        if ($this->glyphAnchor !== null) {
            $data['glyphAnchor'] = $this->glyphAnchor;
        }

        if ($this->markerColor !== null) {
            $data['markerColor'] = $this->markerColor;
        }

        if ($this->iconSize !== null) {
            $data['iconSize'] = $this->iconSize;
        }

        if ($this->iconAnchor !== null) {
            $data['iconAnchor'] = $this->iconAnchor;
        }

        if ($this->popupAnchor !== null) {
            $data['popupAnchor'] = $this->popupAnchor;
        }

        return $data;
    }
}
