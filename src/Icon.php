<?php

namespace arielmejiadev\LeafletForLaravel;

use Illuminate\Contracts\Support\Arrayable;

class Icon implements Arrayable
{
    protected string $url;

    protected string $shadowUrl = 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png';

    protected array $iconSize = [25, 41];

    protected array $shadowSize = [41, 41];

    protected array $iconAnchor = [12, 41];

    protected array $shadowAnchor = [12, 41];

    protected array $popupAnchor = [1, -34];

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public static function color(string $color): static
    {
        return new static(
            'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-'.$color.'.png'
        );
    }

    public static function fromStorage(string $path): static
    {
        return new static(asset('storage/'.ltrim($path, '/')));
    }

    public function size(int $width, int $height): static
    {
        $this->iconSize = [$width, $height];

        return $this;
    }

    public function anchor(int $x, int $y): static
    {
        $this->iconAnchor = [$x, $y];

        return $this;
    }

    public function popupAnchor(int $x, int $y): static
    {
        $this->popupAnchor = [$x, $y];

        return $this;
    }

    public function shadow(string $url, ?int $width = null, ?int $height = null): static
    {
        $this->shadowUrl = $url;

        if ($width !== null && $height !== null) {
            $this->shadowSize = [$width, $height];
        }

        return $this;
    }

    public function shadowAnchor(int $x, int $y): static
    {
        $this->shadowAnchor = [$x, $y];

        return $this;
    }

    public function toArray(): array
    {
        return [
            'iconUrl' => $this->url,
            'shadowUrl' => $this->shadowUrl,
            'iconSize' => $this->iconSize,
            'shadowSize' => $this->shadowSize,
            'iconAnchor' => $this->iconAnchor,
            'shadowAnchor' => $this->shadowAnchor,
            'popupAnchor' => $this->popupAnchor,
        ];
    }
}
