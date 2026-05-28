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

  const streetLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
  })

  const map = L.map(mapEl.value, {
    scrollWheelZoom: false,
    layers: [streetLayer],
  })

  const markerIcon = (color) => {
    const hex = { blue: '#2A81CB', green: '#2AAD27', red: '#CB2B3E' }[color] || '#2A81CB'

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

  const addMarkers = (items, color, group) => {
    items.forEach((m) => {
      const link = m.url ? `<br><a href="${m.url}" target="_blank" style="color: #2563eb;">Visit website</a>` : ''
      L.marker([m.lat, m.lng], { icon: markerIcon(color) })
        .bindPopup(`<b>${m.name}</b><br>${m.address}${link}`)
        .addTo(group)
      L.circle([m.lat, m.lng], {
        radius: 500,
        color: m.circle,
        fillColor: m.circle,
        fillOpacity: 0.12,
        weight: 1,
      }).addTo(group)
    })
  }

  const museums = [
    { lat: 40.7794, lng: -73.9632, name: 'The Metropolitan Museum of Art', address: '1000 Fifth Ave', circle: '#2563eb', url: 'https://www.metmuseum.org' },
    { lat: 40.7614, lng: -73.9776, name: 'Museum of Modern Art (MoMA)', address: '11 W 53rd St', circle: '#2563eb', url: 'https://www.moma.org' },
    { lat: 40.7830, lng: -73.9590, name: 'Guggenheim Museum', address: '1071 Fifth Ave', circle: '#2563eb', url: 'https://www.guggenheim.org' },
  ]

  const stadiums = [
    { lat: 40.8296, lng: -73.9262, name: 'Yankee Stadium', address: '1 E 161st St, Bronx', circle: '#10b981', url: 'https://www.mlb.com/yankees/ballpark' },
    { lat: 40.7505, lng: -73.9934, name: 'Madison Square Garden', address: '4 Pennsylvania Plaza', circle: '#10b981', url: 'https://www.msg.com' },
    { lat: 40.7571, lng: -73.8458, name: 'Citi Field', address: '41 Seaver Way, Queens', circle: '#10b981', url: 'https://www.mlb.com/mets/ballpark' },
  ]

  const landmarks = [
    { lat: 40.6892, lng: -74.0445, name: 'Statue of Liberty', address: 'Liberty Island', circle: '#ef4444', url: 'https://www.nps.gov/stli' },
    { lat: 40.7484, lng: -73.9857, name: 'Empire State Building', address: '350 Fifth Ave', circle: '#ef4444', url: 'https://www.esbnyc.com' },
    { lat: 40.7061, lng: -73.9969, name: 'Brooklyn Bridge', address: 'Manhattan / Brooklyn', circle: '#ef4444', url: 'https://www.nps.gov/brbr' },
  ]

  const museumsGroup = L.layerGroup()
  const stadiumsGroup = L.layerGroup()
  const landmarksGroup = L.layerGroup()

  addMarkers(museums, 'blue', museumsGroup)
  addMarkers(stadiums, 'green', stadiumsGroup)
  addMarkers(landmarks, 'red', landmarksGroup)

  museumsGroup.addTo(map)
  stadiumsGroup.addTo(map)
  landmarksGroup.addTo(map)

  L.control.layers(
    {
      'Street': streetLayer,
      'Light': L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }),
      'Dark': L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }),
    },
    {
      'Museums': museumsGroup,
      'Stadiums': stadiumsGroup,
      'Landmarks': landmarksGroup,
    },
  ).addTo(map)

  const allPoints = [...museums, ...stadiums, ...landmarks].map((p) => [p.lat, p.lng])
  map.fitBounds(allPoints, { padding: [40, 40] })
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
  height: 420px;
}
</style>
