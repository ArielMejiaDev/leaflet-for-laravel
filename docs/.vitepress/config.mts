import { defineConfig } from 'vitepress'

export default defineConfig({
  base: '/leaflet-for-laravel/',
  srcDir: '.',
  srcExclude: ['**/node_modules/**', '**/vendor/**'],
  title: "Leaflet for Laravel",
  description: "An expressive, fluent API for rendering Leaflet.js maps in Laravel applications.",
  themeConfig: {
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Quick Start', link: '/quick-start' }
    ],

    sidebar: [
      {
        text: 'Getting Started',
        items: [
          { text: 'Installation', link: '/installation' },
          { text: 'Quick Start', link: '/quick-start' },
        ]
      },
      {
        text: 'Core Concepts',
        items: [
          { text: 'Maps', link: '/maps' },
          { text: 'Markers', link: '/markers' },
          { text: 'Icons', link: '/icons' },
          { text: 'Layer Control', link: '/layer-control' },
          { text: 'GeoJSON', link: '/geojson' },
        ]
      },
      {
        text: 'Reference',
        items: [
          { text: 'Advanced Example', link: '/advanced-example' },
          { text: 'Blade Directives', link: '/blade-directives' },
          { text: 'Artisan Command', link: '/artisan-command' },
          { text: 'Configuration', link: '/configuration' },
        ]
      }
    ],

    socialLinks: [
      { icon: 'github', link: 'https://github.com/arielmejiadev/leaflet-for-laravel' }
    ]
  }
})
