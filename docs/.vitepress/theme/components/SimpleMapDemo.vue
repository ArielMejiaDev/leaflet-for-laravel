<template>
  <div class="leaflet-demo">
    <div ref="mapEl" class="leaflet-demo-map"></div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const mapEl = ref(null)

onMounted(async () => {
  if (typeof window === 'undefined') return

  const L = await import('leaflet')
  await import('leaflet/dist/leaflet.css')

  const street = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' })

  const map = L.map(mapEl.value, { scrollWheelZoom: false, layers: [street] }).setView([34.1184, -118.3004], 14)

  L.control.layers({
    'Street': street,
    'Light': L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }),
    'Dark': L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }),
  }).addTo(map)

  const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="41" viewBox="0 0 25 41">
    <path d="M12.5 0.5C5.873 0.5 0.5 5.873 0.5 12.5C0.5 21.614 12.5 40.5 12.5 40.5S24.5 21.614 24.5 12.5C24.5 5.873 19.127 0.5 12.5 0.5Z" fill="#2A81CB" stroke="#2c2c2c" stroke-opacity="0.4" stroke-width="1"/>
    <circle cx="12.5" cy="12.5" r="6" fill="white" fill-opacity="0.9"/>
  </svg>`

  const icon = L.icon({
    iconUrl: 'data:image/svg+xml;base64,' + btoa(svg),
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
  })

  L.marker([34.1184, -118.3004], { icon })
    .bindPopup('<b>Griffith Observatory</b><br>2800 E Observatory Rd, Los Angeles')
    .addTo(map)
    .openPopup()
})
</script>

<style>
.leaflet-demo {
  margin: 24px 0;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid var(--vp-c-divider);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
}

.leaflet-demo-map {
  width: 100%;
  height: 350px;
}
</style>
