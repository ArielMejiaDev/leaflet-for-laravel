---
layout: home

hero:
  name: "Leaflet for Laravel"
  text: "Interactive maps with pure PHP"
  tagline: A fluent, expressive API to render Leaflet.js maps in Laravel — no JavaScript required.
  actions:
    - theme: brand
      text: Get Started
      link: /quick-start
    - theme: alt
      text: View on GitHub
      link: https://github.com/arielmejiadev/leaflet-for-laravel

features:
  - title: Pure PHP — Zero JavaScript
    details: Build fully interactive maps writing only PHP. The package generates all the Leaflet.js code for you behind the scenes.
  - title: Fluent, Chainable API
    details: Chain methods like ->center()->zoom()->marker() just like Laravel's Str or Eloquent query builder. Readable, expressive, and easy to learn.
  - title: Markers, Popups & Tooltips
    details: Place markers with popups, tooltips, draggable pins, and colored icons in a single method call. Add circle overlays with one extra chain.
  - title: 7 Built-in Icon Colors + Glyph Icons
    details: Choose from blue, green, red, orange, violet, gold, and yellow markers — or render Font Awesome and Material Design icons directly on pins.
  - title: Multiple Map Types & Layer Control
    details: Offer your users Street, Satellite, Terrain, Light, and Dark base layers with a single method per layer. Leaflet's layer switcher appears automatically.
  - title: Overlay Groups
    details: Organize markers into toggleable categories like Museums, Stadiums, or Landmarks. Users check or uncheck each group independently.
  - title: GeoJSON Support
    details: Render points, lines, and feature collections from arrays, JSON files, or external APIs. Style features with colors and bind popups from properties.
  - title: Blade Directives
    details: Drop @leafletStyles in your head and @leafletMap($map) in your body. That's it — two directives to go from PHP to a live map.
  - title: Livewire & Artisan Ready
    details: Generate map classes with php artisan make:leaflet. Choose between a plain PHP class or a Livewire component with built-in polling support.
  - title: Macros & Conditionals
    details: Extend the Map class with custom macros and use ->when() / ->unless() for conditional logic mid-chain — just like any Laravel fluent class.
  - title: Multiple Maps Per Page
    details: Each LeafletMap::of('id') call creates an isolated instance. Render as many maps as you need in the same view without conflicts.
  - title: Fully Configurable
    details: Publish the config file to set default zoom, dimensions, tile layer, and attribution. Override per-map or via environment variables.
---
