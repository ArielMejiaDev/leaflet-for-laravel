<?php

namespace arielmejiadev\LeafletForLaravel;

use Illuminate\Contracts\Support\Arrayable;

class Circle implements Arrayable
{
    public function __construct(
        protected float $lat,
        protected float $lng,
        protected int $radius = 200,
        protected string $color = 'blue',
        protected string $fillColor = '#3388ff',
        protected float $fillOpacity = 0.15,
    ) {}

    public function toArray(): array
    {
        return [
            'lat' => $this->lat,
            'lng' => $this->lng,
            'radius' => $this->radius,
            'color' => $this->color,
            'fillColor' => $this->fillColor,
            'fillOpacity' => $this->fillOpacity,
        ];
    }
}
