<?php

namespace arielmejiadev\LeafletForLaravel;

use Closure;

class MarkerGroup
{
    /** @var Marker[] */
    protected array $markers = [];

    public function marker(float $lat, float $lng, string|Closure|null $popupOrCallback = null): static
    {
        $marker = new Marker($lat, $lng);

        if (is_string($popupOrCallback)) {
            $marker->popup($popupOrCallback);
        } elseif ($popupOrCallback instanceof Closure) {
            $popupOrCallback($marker);
        }

        $this->markers[] = $marker;

        return $this;
    }

    public function add(Marker $marker): static
    {
        $this->markers[] = $marker;

        return $this;
    }

    /** @return Marker[] */
    public function getMarkers(): array
    {
        return $this->markers;
    }
}
