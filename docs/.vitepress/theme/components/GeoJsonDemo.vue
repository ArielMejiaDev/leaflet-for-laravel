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

  const geojson = {
    type: 'FeatureCollection',
    features: [
      {
        type: 'Feature',
        properties: { popup: '<b>Golden Gate Bridge</b><br>San Francisco', color: '#3388ff' },
        geometry: { type: 'Point', coordinates: [-122.4783, 37.8199] },
      },
      {
        type: 'Feature',
        properties: { popup: '<b>Space Needle</b><br>Seattle', color: '#33cc66' },
        geometry: { type: 'Point', coordinates: [-122.3493, 47.6205] },
      },
      {
        type: 'Feature',
        properties: { popup: '<b>Hollywood Sign</b><br>Los Angeles', color: '#ff6633' },
        geometry: { type: 'Point', coordinates: [-118.3215, 34.1341] },
      },
      {
        type: 'Feature',
        properties: { popup: '<b>Gateway Arch</b><br>St. Louis', color: '#ff3333' },
        geometry: { type: 'Point', coordinates: [-90.1848, 38.6247] },
      },
      {
        type: 'Feature',
        properties: { popup: '<b>Statue of Liberty</b><br>New York', color: '#9966ff' },
        geometry: { type: 'Point', coordinates: [-74.0445, 40.6892] },
      },
      {
        type: 'Feature',
        properties: { color: '#3388ff' },
        geometry: {
          type: 'LineString',
          coordinates: [
            [-122.4783, 37.8199],
            [-122.3493, 47.6205],
          ],
        },
      },
      {
        type: 'Feature',
        properties: { color: '#ff6633' },
        geometry: {
          type: 'LineString',
          coordinates: [
            [-118.3215, 34.1341],
            [-122.4783, 37.8199],
          ],
        },
      },
      {
        type: 'Feature',
        properties: { color: '#9966ff' },
        geometry: {
          type: 'LineString',
          coordinates: [
            [-74.0445, 40.6892],
            [-90.1848, 38.6247],
          ],
        },
      },
    ],
  }

  L.geoJSON(geojson, {
    pointToLayer: (feature, latlng) => {
      return L.circleMarker(latlng, {
        radius: 10,
        fillColor: feature.properties.color || '#3388ff',
        color: '#fff',
        weight: 2,
        fillOpacity: 0.85,
      })
    },
    style: (feature) => {
      if (feature.geometry.type === 'LineString') {
        return { color: feature.properties.color || '#3388ff', weight: 3, opacity: 0.6 }
      }
    },
    onEachFeature: (feature, layer) => {
      if (feature.properties.popup) {
        layer.bindPopup(feature.properties.popup)
      }
    },
  }).addTo(map)

  map.fitBounds([
    [34.1341, -122.4783],
    [47.6205, -74.0445],
  ], { padding: [30, 30] })
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
