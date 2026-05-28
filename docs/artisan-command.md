# Artisan Command

The package provides a `make:leaflet` Artisan command to scaffold map classes quickly.

## Usage

```bash
php artisan make:leaflet StoreLocator
```

You'll be prompted to choose the class type:

```
 What type of class would you like to generate?
 > PHP
   Livewire
```

## PHP class

Selecting **PHP** generates `app/Leaflet/StoreLocator.php`:

```php
<?php

namespace App\Leaflet;

use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Map;
use arielmejiadev\LeafletForLaravel\Marker;

class StoreLocator
{
    public function build(): Map
    {
        return LeafletMap::of('store-locator')
            ->center(39.10995, -84.53747)
            ->zoom(15)
            ->marker(39.10995, -84.53747, fn (Marker $pin) => $pin
                ->popup('<b>Cincinnati Union Terminal</b><br>Museum Center')
                ->icon('blue')
            );
    }
}
```

Use it in your controller:

```php
use App\Leaflet\StoreLocator;

class StoreController extends Controller
{
    public function index(StoreLocator $locator)
    {
        $map = $locator->build();

        return view('stores.index', compact('map'));
    }
}
```

## Livewire component

Selecting **Livewire** generates two files:

### `app/Livewire/StoreLocator.php`

```php
<?php

namespace App\Livewire;

use arielmejiadev\LeafletForLaravel\LeafletMap;
use arielmejiadev\LeafletForLaravel\Map;
use arielmejiadev\LeafletForLaravel\Marker;
use Livewire\Component;

class StoreLocator extends Component
{
    public string $mapHtml = '';

    public function mount(): void
    {
        $this->mapHtml = $this->buildMap()->toHtml();
    }

    public function buildMap(): Map
    {
        return LeafletMap::of('store-locator')
            ->center(39.10995, -84.53747)
            ->zoom(15)
            ->marker(39.10995, -84.53747, fn (Marker $pin) => $pin
                ->popup('<b>Cincinnati Union Terminal</b><br>Museum Center')
                ->icon('blue')
            );
    }

    /**
     * Rebuild the map. Useful with Livewire polling:
     * <div wire:poll.5s="refreshMap">
     */
    public function refreshMap(): void
    {
        $this->mapHtml = $this->buildMap()->toHtml();
    }

    public function render()
    {
        return view('livewire.store-locator');
    }
}
```

### `resources/views/livewire/store-locator.blade.php`

```blade
<div>
    {{-- To enable polling, add wire:poll.5s="refreshMap" to the div above --}}
    {!! $mapHtml !!}
</div>
```

### Livewire polling

To enable live updates (e.g., tracking moving assets), add `wire:poll` to the root `<div>`:

```blade
<div wire:poll.5s="refreshMap">
    {!! $mapHtml !!}
</div>
```

This calls `refreshMap()` every 5 seconds, which rebuilds the map with fresh data.

### Using the Livewire component

```blade
<head>
    @leafletStyles
</head>
<body>
    <livewire:store-locator />
</body>
```
