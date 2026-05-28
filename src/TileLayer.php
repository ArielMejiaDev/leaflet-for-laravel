<?php

namespace arielmejiadev\LeafletForLaravel;

use Illuminate\Contracts\Support\Arrayable;

class TileLayer implements Arrayable
{
    public function __construct(
        protected string $name,
        protected string $url,
        protected int $maxZoom = 19,
        protected string $attribution = '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'url' => $this->url,
            'maxZoom' => $this->maxZoom,
            'attribution' => $this->attribution,
        ];
    }
}
