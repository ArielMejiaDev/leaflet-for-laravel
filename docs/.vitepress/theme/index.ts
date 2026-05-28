import DefaultTheme from 'vitepress/theme'
import './custom.css'
import Layout from './Layout.vue'
import LeafletDemo from './components/LeafletDemo.vue'
import SimpleMapDemo from './components/SimpleMapDemo.vue'
import MarkersDemo from './components/MarkersDemo.vue'
import IconsDemo from './components/IconsDemo.vue'
import LayerControlDemo from './components/LayerControlDemo.vue'
import GeoJsonDemo from './components/GeoJsonDemo.vue'

export default {
  extends: DefaultTheme,
  Layout,
  enhanceApp({ app }) {
    app.component('LeafletDemo', LeafletDemo)
    app.component('SimpleMapDemo', SimpleMapDemo)
    app.component('MarkersDemo', MarkersDemo)
    app.component('IconsDemo', IconsDemo)
    app.component('LayerControlDemo', LayerControlDemo)
    app.component('GeoJsonDemo', GeoJsonDemo)
  },
}
