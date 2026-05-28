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

  const osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' })
  const osmHot = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap, HOT' })
  const openTopo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', { maxZoom: 17, attribution: '&copy; OpenTopoMap' })
  const esriSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 18, attribution: '&copy; Esri' })
  const esriStreet = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', { maxZoom: 18, attribution: '&copy; Esri' })
  const esriTopo = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', { maxZoom: 18, attribution: '&copy; Esri' })
  const positron = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19, attribution: '&copy; CARTO' })
  const darkMatter = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19, attribution: '&copy; CARTO' })
  const voyager = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { maxZoom: 19, attribution: '&copy; CARTO' })
  const cyclOSM = L.tileLayer('https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; CyclOSM' })

  const map = L.map(mapEl.value, {
    scrollWheelZoom: false,
    center: [36.1069, -112.1129],
    zoom: 11,
    layers: [osm],
  })

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

  L.marker([36.1069, -112.1129], { icon: markerIcon('#CB2B3E') })
    .bindPopup('<b>Grand Canyon Village</b><br>South Rim Visitor Center')
    .addTo(map)

  L.marker([36.0544, -112.1401], { icon: markerIcon('#2A81CB') })
    .bindPopup('<b>Hermit\'s Rest</b><br>West end of Hermit Road')
    .addTo(map)

  L.marker([36.0590, -111.8387], { icon: markerIcon('#2AAD27') })
    .bindPopup('<b>Desert View Watchtower</b><br>East entrance viewpoint')
    .addTo(map)

  L.control.layers({
    'OpenStreetMap': osm,
    'OSM HOT': osmHot,
    'OpenTopoMap': openTopo,
    'Esri Satellite': esriSatellite,
    'Esri Street': esriStreet,
    'Esri Topo': esriTopo,
    'Light': positron,
    'Dark': darkMatter,
    'CartoDB Voyager': voyager,
    'CyclOSM': cyclOSM,
  }).addTo(map)
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
