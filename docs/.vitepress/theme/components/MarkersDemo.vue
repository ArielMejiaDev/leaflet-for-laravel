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

  const map = L.map(mapEl.value, { scrollWheelZoom: false, layers: [street] })

  L.control.layers({
    'Street': street,
    'Light': L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }),
    'Dark': L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }),
  }).addTo(map)

  const markerIcon = (color) => {
    const hex = { blue: '#2A81CB', green: '#2AAD27', red: '#CB2B3E', orange: '#CB8427' }[color] || '#2A81CB'
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

  const markers = [
    { lat: 42.3467, lng: -71.0972, name: 'Fenway Park', address: 'Boston, MA', color: 'blue', url: 'https://www.mlb.com/redsox/ballpark' },
    { lat: 40.7505, lng: -73.9934, name: 'Madison Square Garden', address: 'New York, NY', color: 'green', url: 'https://www.msg.com' },
    { lat: 38.8913, lng: -77.0200, name: 'Smithsonian', address: 'Washington, DC', color: 'red', url: 'https://www.si.edu' },
    { lat: 41.8796, lng: -87.6237, name: 'Art Institute of Chicago', address: 'Chicago, IL', color: 'orange', url: 'https://www.artic.edu' },
  ]

  const points = []
  markers.forEach((m) => {
    const link = `<br><a href="${m.url}" target="_blank" style="color: #2563eb;">Visit website</a>`
    L.marker([m.lat, m.lng], { icon: markerIcon(m.color) })
      .bindPopup(`<b>${m.name}</b><br>${m.address}${link}`)
      .addTo(map)
    L.circle([m.lat, m.lng], { radius: 3000, color: '#6366f1', fillColor: '#6366f1', fillOpacity: 0.08, weight: 1 }).addTo(map)
    points.push([m.lat, m.lng])
  })

  map.fitBounds(points, { padding: [40, 40] })
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
