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
      primary: '#002B49',   // Davao Research Journal Navy
      secondary: '#D4A017', // DRJ Gold
      accent: '#0284c7',
      dark: '#0f172a',
      positive: '#10b981',
      negative: '#ef4444',
      info: '#06b6d4',
      warning: '#f59e0b'
    }
  }
})

app.mount('#app')
