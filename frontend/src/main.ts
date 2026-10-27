import { createApp } from 'vue'
import { Quasar, Notify, Dialog, Loading } from 'quasar'
import { createPinia } from 'pinia'

// Import Quasar css
import 'quasar/src/css/index.sass'

import App from './App.vue'

const app = createApp(App)
app.use(createPinia())
app.use(Quasar, {
  plugins: {
    Notify,
    Dialog,
    Loading
  },
  config: {
    brand: {
      primary: '#002B49',   // Philair Deep Institutional Navy
      secondary: '#D4A017', // Philair Gold / Amber
      accent: '#5C2D91',    // Philair Royal Purple
      dark: '#00172B',      // Dark Frame-0 Navy
      positive: '#10b981',
      negative: '#ef4444',
      info: '#06b6d4',
      warning: '#f59e0b'
    }
  }
})

app.mount('#app')
