<script setup>
import DefaultTheme from 'vitepress/theme'
import { ref, onMounted } from 'vue'
import LeafletDemo from './components/LeafletDemo.vue'

const { Layout } = DefaultTheme

const highlightedCode = ref('')

const phpSource = `use arielmejiadev\\LeafletForLaravel\\LeafletMap;
use arielmejiadev\\LeafletForLaravel\\Marker;

$map = LeafletMap::of('nyc-explorer')
    ->center(40.7580, -73.9855)
    ->zoom(13)

    // Base layers — user picks one
    ->baseLayer('Street', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png')
    ->baseLayer('Light', 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png')
    ->baseLayer('Dark', 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png')

    // Museums
    ->overlayGroup('Museums', fn ($group) => $group
        ->marker(40.7794, -73.9632, fn (Marker $pin) => $pin
            ->popup('<b>The Met</b><br><a href="https://www.metmuseum.org" target="_blank">Visit website</a>')
            ->icon('blue')
        )
        ->marker(40.7614, -73.9776, fn (Marker $pin) => $pin
            ->popup('<b>MoMA</b><br><a href="https://www.moma.org" target="_blank">Visit website</a>')
            ->icon('blue')
        )
        ->marker(40.7830, -73.9590, fn (Marker $pin) => $pin
            ->popup('<b>Guggenheim</b><br><a href="https://www.guggenheim.org" target="_blank">Visit website</a>')
            ->icon('blue')
        )
    )

    // Stadiums
    ->overlayGroup('Stadiums', fn ($group) => $group
        ->marker(40.8296, -73.9262, fn (Marker $pin) => $pin
            ->popup('<b>Yankee Stadium</b><br><a href="https://www.mlb.com/yankees" target="_blank">Visit website</a>')
            ->icon('green')
        )
        ->marker(40.7505, -73.9934, fn (Marker $pin) => $pin
            ->popup('<b>Madison Square Garden</b><br><a href="https://www.msg.com" target="_blank">Visit website</a>')
            ->icon('green')
        )
    )

    // Landmarks
    ->overlayGroup('Landmarks', fn ($group) => $group
        ->marker(40.6892, -74.0445, fn (Marker $pin) => $pin
            ->popup('<b>Statue of Liberty</b><br><a href="https://www.nps.gov/stli" target="_blank">Visit website</a>')
            ->icon('red')
        )
        ->marker(40.7484, -73.9857, fn (Marker $pin) => $pin
            ->popup('<b>Empire State Building</b><br><a href="https://www.esbnyc.com" target="_blank">Visit website</a>')
            ->icon('red')
        )
    )
    ->fitBounds();`

onMounted(async () => {
  const { codeToHtml } = await import('shiki')
  highlightedCode.value = await codeToHtml(phpSource, {
    lang: 'php',
    themes: {
      light: 'github-light',
      dark: 'github-dark',
    },
    defaultColor: false,
  })
})
</script>

<template>
  <Layout>
    <template #home-features-before>
      <div class="home-demo">
        <h2>See it in action</h2>
        <p>This interactive map is what the package generates — built entirely in PHP. Toggle the layer checkboxes and click markers to see popups with links.</p>
        <LeafletDemo />
        <details class="home-code-details">
          <summary>See the PHP code that generates this map</summary>
          <div class="home-code-block" v-html="highlightedCode"></div>
        </details>
      </div>
    </template>
  </Layout>
</template>

<style>
.home-demo {
  max-width: 900px;
  margin: 0 auto;
  padding: 48px 24px 0;
}

.home-demo h2 {
  font-size: 1.8rem;
  font-weight: 700;
  margin-bottom: 8px;
}

.home-demo p {
  color: var(--vp-c-text-2);
  margin-bottom: 20px;
}

.home-code-details {
  margin-top: 16px;
  margin-bottom: 24px;
}

.home-code-details summary {
  cursor: pointer;
  font-weight: 600;
  color: var(--vp-c-brand-1);
  padding: 8px 0;
}

.home-code-details summary:hover {
  color: var(--vp-c-brand-2);
}

.home-code-block pre {
  background: #f6f8fa;
  border-radius: 12px;
  padding: 20px 24px;
  overflow-x: auto;
  margin-top: 12px;
  font-size: 0.85rem;
  line-height: 1.6;
}

.dark .home-code-block pre {
  background: #161b22;
}

.home-code-block code {
  font-family: var(--vp-font-family-mono);
}

/* Shiki dual-theme: light/dark mode switching */
.home-code-block .shiki {
  background-color: #f6f8fa !important;
}

.home-code-block .shiki span {
  color: var(--shiki-light);
}

.dark .home-code-block .shiki {
  background-color: #161b22 !important;
}

.dark .home-code-block .shiki span {
  color: var(--shiki-dark);
}
</style>
