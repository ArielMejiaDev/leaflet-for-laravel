<?php

namespace arielmejiadev\LeafletForLaravel;

use arielmejiadev\LeafletForLaravel\Enums\GlyphEnum;
use Illuminate\Contracts\Support\Arrayable;

class Marker implements Arrayable
{
    protected ?string $popup = null;

    protected ?string $tooltip = null;

    protected string|Icon|null $icon = null;

    protected bool $draggable = false;

    protected ?Circle $circle = null;

    protected ?GlyphIcon $glyphIcon = null;

    public function __construct(
        protected float $lat,
        protected float $lng,
    ) {}

    public function popup(string $content): static
    {
        $this->popup = $content;

        return $this;
    }

    public function tooltip(string $content): static
    {
        $this->tooltip = $content;

        return $this;
    }

    public function icon(string|Icon $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function draggable(bool $draggable = true): static
    {
        $this->draggable = $draggable;

        return $this;
    }

    public function circle(
        int $radius = 200,
        string $color = 'blue',
        string $fillColor = '#3388ff',
        float $fillOpacity = 0.15,
    ): static {
        $this->circle = new Circle($this->lat, $this->lng, $radius, $color, $fillColor, $fillOpacity);

        return $this;
    }

    public function glyph(GlyphEnum|GlyphIcon $icon, ?string $color = null): static
    {
        $this->glyphIcon = $icon instanceof GlyphEnum ? GlyphIcon::from($icon) : $icon;

        if ($color !== null) {
            $this->glyphIcon->color($color);
        }

        return $this;
    }

    public function getGlyphCssUrl(): ?string
    {
        return $this->glyphIcon?->getCssUrl();
    }

    public function toArray(): array
    {
        $data = [
            'lat' => $this->lat,
            'lng' => $this->lng,
            'popup' => $this->popup,
            'tooltip' => $this->tooltip,
            'draggable' => $this->draggable,
        ];

        if ($this->icon instanceof Icon) {
            $data['icon'] = $this->icon->toArray();
        } elseif (is_string($this->icon)) {
            $data['icon'] = Icon::color($this->icon)->toArray();
        }

        if ($this->circle) {
            $data['circle'] = $this->circle->toArray();
        }

        if ($this->glyphIcon) {
            $data['glyphIcon'] = $this->glyphIcon->toArray();
        }

        return $data;
    }
}
