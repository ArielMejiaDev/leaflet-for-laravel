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

  const light = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19, attribution: '&copy; CARTO' })

  const map = L.map(mapEl.value, { scrollWheelZoom: false, layers: [light] })

  L.control.layers({
    'Light': light,
    'Street': L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }),
    'Dark': L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }),
  }).addTo(map)

  const markerIcon = (hex) => {
    const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="41" viewBox="0 0 25 41">
      <path d="M12.5 0.5C5.873 0.5 0.5 5.873 0.5 12.5C0.5 21.614 12.5 40.5 12.5 40.5S24.5 21.614 24.5 12.5C24.5 5.873 19.127 0.5 12.5 0.5Z" fill="${hex}" stroke="#2c2c2c" stroke-opacity="0.4" stroke-width="1"/>
      <circle cx="12.5" cy="12.5" r="6" fill="white" fill-opacity="0.9"/>
    </svg>`
    return L.icon({
      iconUrl: 'data:image/svg+xml;base64,' + btoa(svg),
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
    })
  }

  const pins = [
    { lat: 40.7794, lng: -73.9632, name: 'The Met', hex: '#2A81CB', label: 'blue' },
    { lat: 40.7614, lng: -73.9776, name: 'MoMA', hex: '#2AAD27', label: 'green' },
    { lat: 40.7484, lng: -73.9857, name: 'Empire State', hex: '#CB2B3E', label: 'red' },
    { lat: 40.7505, lng: -73.9934, name: 'MSG', hex: '#CB8427', label: 'orange' },
    { lat: 40.7830, lng: -73.9590, name: 'Guggenheim', hex: '#9C2BCB', label: 'violet' },
    { lat: 40.7527, lng: -73.9772, name: 'Grand Central', hex: '#CFB53B', label: 'gold' },
    { lat: 40.7580, lng: -73.9855, name: 'Times Square', hex: '#DAA520', label: 'yellow' },
  ]

  const points = []
  pins.forEach((p) => {
    L.marker([p.lat, p.lng], { icon: markerIcon(p.hex) })
      .bindPopup(`<b>${p.name}</b><br>Icon color: <code>${p.label}</code>`)
      .addTo(map)
    points.push([p.lat, p.lng])
  })

  map.fitBounds(points, { padding: [30, 30] })
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
  height: 380px;
}
</style>
