<?php

namespace arielmejiadev\LeafletForLaravel;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\Tappable;
use JsonSerializable;
use Stringable;

class Map implements Arrayable, Htmlable, Jsonable, JsonSerializable, Stringable
{
    use Conditionable, Macroable, Tappable;

    protected ?float $centerLat = null;

    protected ?float $centerLng = null;

    protected int $zoom;

    protected string $width;

    protected string $height;

    protected string $tileLayerUrl;

    protected int $maxZoom;

    protected string $attribution;

    protected bool $scrollWheelZoom = true;

    /** @var Marker[] */
    protected array $markers = [];

    protected bool $fitBounds = false;

    protected float $boundsPadding = 0.2;

    /** @var TileLayer[] */
    protected array $baseLayers = [];

    /** @var array<string, MarkerGroup> */
    protected array $overlayGroups = [];

    protected ?array $geoJson = null;

    protected bool $locateUser = false;

    protected int $locateMaxZoom = 16;

    public function __construct(protected string $id)
    {
        $this->tileLayerUrl = config('leaflet-for-laravel.tile_layer', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png');
        $this->maxZoom = config('leaflet-for-laravel.max_zoom', 19);
        $this->zoom = config('leaflet-for-laravel.zoom', 13);
        $this->attribution = config('leaflet-for-laravel.attribution', '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>');
        $this->width = config('leaflet-for-laravel.width', '100%');
        $this->height = config('leaflet-for-laravel.height', '400px');
    }

    public function center(float $lat, float $lng): static
    {
        $this->centerLat = $lat;
        $this->centerLng = $lng;

        return $this;
    }

    public function zoom(int $level): static
    {
        $this->zoom = $level;

        return $this;
    }

    public function width(string $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function height(string $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function tileLayer(string $url, ?int $maxZoom = null, ?string $attribution = null): static
    {
        $this->tileLayerUrl = $url;

        if ($maxZoom !== null) {
            $this->maxZoom = $maxZoom;
        }

        if ($attribution !== null) {
            $this->attribution = $attribution;
        }

        return $this;
    }

    public function maxZoom(int $maxZoom): static
    {
        $this->maxZoom = $maxZoom;

        return $this;
    }

    public function attribution(string $attribution): static
    {
        $this->attribution = $attribution;

        return $this;
    }

    public function scrollWheelZoom(bool $enabled = true): static
    {
        $this->scrollWheelZoom = $enabled;

        return $this;
    }

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

    public function markers(array $markers): static
    {
        foreach ($markers as $data) {
            if ($data instanceof Marker) {
                $this->markers[] = $data;
            } elseif (is_array($data)) {
                $marker = new Marker($data[0], $data[1]);

                if (isset($data[2])) {
                    $marker->popup($data[2]);
                }

                if (isset($data[3])) {
                    $marker->icon($data[3]);
                }

                $this->markers[] = $marker;
            }
        }

        return $this;
    }

    public function fitBounds(float $padding = 0.2): static
    {
        $this->fitBounds = true;
        $this->boundsPadding = $padding;

        return $this;
    }

    public function baseLayer(string $name, string $url, int $maxZoom = 19, ?string $attribution = null): static
    {
        $this->baseLayers[] = new TileLayer(
            $name,
            $url,
            $maxZoom,
            $attribution ?? $this->attribution,
        );

        return $this;
    }

    public function overlayGroup(string $name, Closure|array $markersOrCallback): static
    {
        $group = new MarkerGroup;

        if ($markersOrCallback instanceof Closure) {
            $markersOrCallback($group);
        } else {
            foreach ($markersOrCallback as $marker) {
                if ($marker instanceof Marker) {
                    $group->add($marker);
                }
            }
        }

        $this->overlayGroups[$name] = $group;

        return $this;
    }

    public function geoJson(array $data): static
    {
        $this->geoJson = $data;

        return $this;
    }

    public function locateUser(int $maxZoom = 16): static
    {
        $this->locateUser = true;
        $this->locateMaxZoom = $maxZoom;

        return $this;
    }

    // ──────────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────────

    public function getId(): string
    {
        return $this->id;
    }

    public function getContainerId(): string
    {
        return 'leaflet-'.$this->id;
    }

    public function getWidth(): string
    {
        return $this->width;
    }

    public function getHeight(): string
    {
        return $this->height;
    }

    public function hasLayerControl(): bool
    {
        return count($this->baseLayers) > 0 || count($this->overlayGroups) > 0;
    }

    // ──────────────────────────────────────────────
    // Terminal methods
    // ──────────────────────────────────────────────

    public function toHtml(): string
    {
        $containerId = e($this->getContainerId());
        $style = "width: {$this->width}; height: {$this->height};";

        $glyphHtml = $this->buildGlyphResourceTags();

        return "{$glyphHtml}<div id=\"{$containerId}\" style=\"{$style}\"></div>\n<script>\n{$this->toScript()}\n</script>";
    }

    public function toScript(): string
    {
        $lines = [];
        $mapVar = $this->jsVarName();

        if ($this->hasLayerControl()) {
            $lines = array_merge($lines, $this->buildLayerControlScript($mapVar));
        } else {
            $lines[] = "var {$mapVar} = L.map({$this->jsString($this->getContainerId())}{$this->buildMapOptions()});";
            $lines[] = $this->buildTileLayerLine($this->tileLayerUrl, $this->maxZoom, $this->attribution, $mapVar);
        }

        if ($this->centerLat !== null && $this->centerLng !== null && ! $this->fitBounds) {
            $lines[] = "{$mapVar}.setView([{$this->centerLat}, {$this->centerLng}], {$this->zoom});";
        }

        $markerVars = [];
        foreach ($this->markers as $i => $marker) {
            $varName = "m{$i}";
            $markerVars[] = $varName;
            $lines = array_merge($lines, $this->buildMarkerLines($marker, $varName, $mapVar));
        }

        if ($this->geoJson !== null) {
            $lines = array_merge($lines, $this->buildGeoJsonLines($mapVar));
        }

        $lines = array_merge($lines, $this->buildFitBoundsLines($mapVar, $markerVars));

        if ($this->locateUser) {
            $lines = array_merge($lines, $this->buildLocateLines($mapVar));
        }

        $indented = implode("\n", array_map(fn (string $l) => "    {$l}", $lines));

        return "(function() {\n{$indented}\n})();";
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'center' => $this->centerLat !== null ? ['lat' => $this->centerLat, 'lng' => $this->centerLng] : null,
            'zoom' => $this->zoom,
            'width' => $this->width,
            'height' => $this->height,
            'tileLayer' => $this->tileLayerUrl,
            'maxZoom' => $this->maxZoom,
            'attribution' => $this->attribution,
            'scrollWheelZoom' => $this->scrollWheelZoom,
            'fitBounds' => $this->fitBounds,
            'boundsPadding' => $this->boundsPadding,
            'markers' => array_map(fn (Marker $m) => $m->toArray(), $this->markers),
            'locateUser' => $this->locateUser,
        ];

        if (! empty($this->baseLayers)) {
            $data['baseLayers'] = array_map(fn (TileLayer $l) => $l->toArray(), $this->baseLayers);
        }

        if (! empty($this->overlayGroups)) {
            $data['overlayGroups'] = [];

            foreach ($this->overlayGroups as $name => $group) {
                $data['overlayGroups'][$name] = array_map(fn (Marker $m) => $m->toArray(), $group->getMarkers());
            }
        }

        if ($this->geoJson !== null) {
            $data['geoJson'] = $this->geoJson;
        }

        return $data;
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }

    // ──────────────────────────────────────────────
    // Glyph resource auto-detection
    // ──────────────────────────────────────────────

    /** @var array<string, true> Tracks glyph resources already emitted across all map instances */
    protected static array $emittedGlyphResources = [];

    /** Reset emitted glyph resources tracking (useful for testing). */
    public static function resetGlyphResources(): void
    {
        static::$emittedGlyphResources = [];
    }

    protected function buildGlyphResourceTags(): string
    {
        $cssUrls = [];

        foreach ($this->markers as $marker) {
            $url = $marker->getGlyphCssUrl();
            if ($url !== null) {
                $cssUrls[$url] = true;
            }
        }

        foreach ($this->overlayGroups as $group) {
            foreach ($group->getMarkers() as $marker) {
                $url = $marker->getGlyphCssUrl();
                if ($url !== null) {
                    $cssUrls[$url] = true;
                }
            }
        }

        if (empty($cssUrls)) {
            return '';
        }

        $html = '';

        foreach (array_keys($cssUrls) as $url) {
            if (! isset(static::$emittedGlyphResources[$url])) {
                $html .= '<link rel="stylesheet" href="'.e($url).'"/>'."\n";
                static::$emittedGlyphResources[$url] = true;
            }
        }

        if (! isset(static::$emittedGlyphResources['__glyph_plugin__'])) {
            $html .= '<script src="https://cdn.jsdelivr.net/gh/Leaflet/Leaflet.Icon.Glyph@gh-pages/Leaflet.Icon.Glyph.js"></script>'."\n";
            static::$emittedGlyphResources['__glyph_plugin__'] = true;
        }

        return $html;
    }

    // ──────────────────────────────────────────────
    // JavaScript generation helpers
    // ──────────────────────────────────────────────

    protected function jsVarName(): string
    {
        return 'map_'.preg_replace('/[^a-zA-Z0-9_]/', '_', $this->id);
    }

    protected function jsString(string $value): string
    {
        return json_encode($value);
    }

    protected function buildMapOptions(): string
    {
        $options = [];

        if (! $this->scrollWheelZoom) {
            $options[] = 'scrollWheelZoom: false';
        }

        return count($options) > 0 ? ', {'.implode(', ', $options).'}' : '';
    }

    protected function buildTileLayerLine(string $url, int $maxZoom, string $attribution, string $mapVar): string
    {
        return "L.tileLayer({$this->jsString($url)}, {maxZoom: {$maxZoom}, attribution: {$this->jsString($attribution)}}).addTo({$mapVar});";
    }

    /**
     * @param  array<int, string>  $extraLayerVars  Collects additional variable names (e.g. circles) for inclusion in a layer group.
     */
    protected function buildMarkerLines(Marker $marker, string $varName, string $mapVar, bool $addToMap = true, array &$extraLayerVars = []): array
    {
        $lines = [];
        $data = $marker->toArray();

        $options = [];
        if (isset($data['glyphIcon'])) {
            $g = $data['glyphIcon'];
            $glyphOpts = ["prefix: {$this->jsString($g['prefix'])}", "glyph: {$this->jsString($g['glyph'])}", "glyphColor: {$this->jsString($g['glyphColor'])}"];
            if (isset($g['glyphSize'])) {
                $glyphOpts[] = "glyphSize: {$this->jsString($g['glyphSize'])}";
            }
            if (isset($g['glyphAnchor'])) {
                $glyphOpts[] = 'glyphAnchor: ['.implode(', ', $g['glyphAnchor']).']';
            }

            $hex = isset($g['markerColor']) ? $this->glyphMarkerColorHex($g['markerColor']) : '#2A81CB';
            $glyphOpts[] = "iconUrl: {$this->jsString($this->glyphMarkerSvgUri($hex))}";
            $iconSize = $g['iconSize'] ?? [25, 41];
            $iconAnchor = $g['iconAnchor'] ?? [12, 41];
            $popupAnchor = $g['popupAnchor'] ?? [1, -34];
            $glyphOpts[] = 'iconSize: ['.implode(', ', $iconSize).']';
            $glyphOpts[] = 'iconAnchor: ['.implode(', ', $iconAnchor).']';
            $glyphOpts[] = 'popupAnchor: ['.implode(', ', $popupAnchor).']';

            $options[] = 'icon: L.icon.glyph({'.implode(', ', $glyphOpts).'})';
        } elseif (isset($data['icon'])) {
            $options[] = 'icon: L.icon('.json_encode($data['icon']).')';
        }
        if ($data['draggable']) {
            $options[] = 'draggable: true';
        }

        $optionsStr = count($options) > 0 ? ', {'.implode(', ', $options).'}' : '';
        $addStr = $addToMap ? ".addTo({$mapVar})" : '';

        $line = "var {$varName} = L.marker([{$data['lat']}, {$data['lng']}]{$optionsStr}){$addStr}";

        if ($data['popup'] !== null) {
            $line .= ".bindPopup({$this->jsString($data['popup'])})";
        }

        if ($data['tooltip'] !== null) {
            $line .= ".bindTooltip({$this->jsString($data['tooltip'])})";
        }

        $lines[] = $line.';';

        if (isset($data['circle'])) {
            $c = $data['circle'];
            $circleJs = "L.circle([{$c['lat']}, {$c['lng']}], {color: {$this->jsString($c['color'])}, fillColor: {$this->jsString($c['fillColor'])}, fillOpacity: {$c['fillOpacity']}, radius: {$c['radius']}})";

            if ($addToMap) {
                $lines[] = "{$circleJs}.addTo({$mapVar});";
            } else {
                $circleVar = "{$varName}_circle";
                $lines[] = "var {$circleVar} = {$circleJs};";
                $extraLayerVars[] = $circleVar;
            }
        }

        return $lines;
    }

    protected function buildLayerControlScript(string $mapVar): array
    {
        $lines = [];

        // Base tile layers
        $baseVarMap = [];
        foreach ($this->baseLayers as $i => $layer) {
            $data = $layer->toArray();
            $varName = "base_{$i}";
            $baseVarMap[$data['name']] = $varName;
            $lines[] = "var {$varName} = L.tileLayer({$this->jsString($data['url'])}, {maxZoom: {$data['maxZoom']}, attribution: {$this->jsString($data['attribution'])}});";
        }

        if (empty($baseVarMap)) {
            $baseVarMap['OpenStreetMap'] = 'base_0';
            $lines[] = "var base_0 = L.tileLayer({$this->jsString($this->tileLayerUrl)}, {maxZoom: {$this->maxZoom}, attribution: {$this->jsString($this->attribution)}});";
        }

        // Overlay groups
        $overlayVarMap = [];
        $allOverlayMarkerVars = [];
        $oi = 0;

        foreach ($this->overlayGroups as $name => $group) {
            $groupLayerVars = [];

            foreach ($group->getMarkers() as $mi => $marker) {
                $mVar = "om_{$oi}_{$mi}";
                $groupLayerVars[] = $mVar;
                $allOverlayMarkerVars[] = $mVar;
                $extraVars = [];
                $lines = array_merge($lines, $this->buildMarkerLines($marker, $mVar, $mapVar, addToMap: false, extraLayerVars: $extraVars));
                $groupLayerVars = array_merge($groupLayerVars, $extraVars);
            }

            $ovVar = "overlay_{$oi}";
            $overlayVarMap[$name] = $ovVar;
            $lines[] = "var {$ovVar} = L.layerGroup([".implode(', ', $groupLayerVars).']);';
            $oi++;
        }

        // Map initialization with default layers
        $defaultLayers = [array_values($baseVarMap)[0]];
        $defaultLayers = array_merge($defaultLayers, array_values($overlayVarMap));

        $mapOptions = ['layers: ['.implode(', ', $defaultLayers).']'];
        if (! $this->scrollWheelZoom) {
            $mapOptions[] = 'scrollWheelZoom: false';
        }

        $lines[] = "var {$mapVar} = L.map({$this->jsString($this->getContainerId())}, {".implode(', ', $mapOptions).'});';

        // Layer control
        $baseEntries = [];
        foreach ($baseVarMap as $name => $var) {
            $baseEntries[] = "{$this->jsString($name)}: {$var}";
        }
        $overlayEntries = [];
        foreach ($overlayVarMap as $name => $var) {
            $overlayEntries[] = "{$this->jsString($name)}: {$var}";
        }

        $lines[] = 'L.control.layers({'.implode(', ', $baseEntries).'}, {'.implode(', ', $overlayEntries).'}).addTo('.$mapVar.');';

        // Store overlay marker vars for fitBounds
        $this->_overlayMarkerVars = $allOverlayMarkerVars;

        return $lines;
    }

    /** @var string[] Temporary storage for overlay marker variable names during script generation */
    protected array $_overlayMarkerVars = [];

    protected function buildGeoJsonLines(string $mapVar): array
    {
        $geoVarName = 'geo_'.$this->jsVarName();

        return [
            "var {$geoVarName}_data = ".json_encode($this->geoJson).';',
            "var {$geoVarName} = L.geoJSON({$geoVarName}_data, {",
            '    pointToLayer: function(feature, latlng) {',
            "        var style = {radius: 10, fillColor: '#3388ff', color: '#fff', weight: 2, opacity: 1, fillOpacity: 0.85};",
            '        if (feature.properties && feature.properties.color) { style.fillColor = feature.properties.color; }',
            '        return L.circleMarker(latlng, style);',
            '    },',
            '    style: function(feature) {',
            '        var s = {weight: 3, opacity: 0.6};',
            '        if (feature.properties && feature.properties.color) { s.color = feature.properties.color; }',
            '        return s;',
            '    },',
            '    onEachFeature: function(feature, layer) {',
            '        if (feature.properties && feature.properties.popup) { layer.bindPopup(feature.properties.popup); }',
            '    }',
            "}).addTo({$mapVar});",
        ];
    }

    protected function buildFitBoundsLines(string $mapVar, array $markerVars): array
    {
        if (! $this->fitBounds) {
            // Auto-fit if no explicit center and there are markers
            if ($this->centerLat === null && (count($markerVars) > 0 || ! empty($this->_overlayMarkerVars) || $this->geoJson !== null)) {
                $this->fitBounds = true;
            } else {
                return [];
            }
        }

        if ($this->geoJson !== null) {
            $geoVarName = 'geo_'.$this->jsVarName();

            return ["{$mapVar}.fitBounds({$geoVarName}.getBounds().pad({$this->boundsPadding}));"];
        }

        $allVars = array_merge($markerVars, $this->_overlayMarkerVars);

        if (empty($allVars)) {
            return [];
        }

        return ["{$mapVar}.fitBounds(L.featureGroup([".implode(', ', $allVars)."]).getBounds().pad({$this->boundsPadding}));"];
    }

    protected function buildLocateLines(string $mapVar): array
    {
        return [
            "{$mapVar}.on('locationfound', function(e) {",
            "    L.marker(e.latlng).addTo({$mapVar}).bindPopup('You are within ' + Math.round(e.accuracy) + ' meters from this point').openPopup();",
            "    L.circle(e.latlng, {radius: e.accuracy}).addTo({$mapVar});",
            '});',
            "{$mapVar}.on('locationerror', function(e) { console.warn(e.message); });",
            "{$mapVar}.locate({setView: false, maxZoom: {$this->locateMaxZoom}});",
        ];
    }

    protected function glyphMarkerSvgUri(string $hex): string
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="41" viewBox="0 0 25 41">'
            .'<path d="M12.5 0.5C5.873 0.5 0.5 5.873 0.5 12.5C0.5 21.614 12.5 40.5 12.5 40.5S24.5 21.614 24.5 12.5C24.5 5.873 19.127 0.5 12.5 0.5Z" fill="'.$hex.'" stroke="'.($hex === '#3D3D3D' ? '#1a1a1a' : '#2c2c2c').'" stroke-opacity="0.4" stroke-width="1"/>'
            .'</svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    protected function glyphMarkerColorHex(string $name): string
    {
        return match ($name) {
            'blue' => '#2A81CB',
            'green' => '#2AAD27',
            'red' => '#CB2B3E',
            'orange' => '#CB8427',
            'violet' => '#9C2BCB',
            'gold' => '#CAC428',
            'yellow' => '#FFD326',
            'grey' => '#7B7B7B',
            'black' => '#3D3D3D',
            default => '#2A81CB',
        };
    }
}
