import { createPinia } from 'pinia'
import { createSSRApp } from 'vue'
import App from './App.vue'
import 'uno.css'
import './styles/reset.scss'
// import router from './router'
// import '@unocss/reset/tailwind.css'
// import '@unocss/reset/tailwind-compat.css'
const pinia = createPinia()

export function createApp() {
  const app = createSSRApp(App)
  app.use(pinia)
  // app.use(router)

  return {
    app,
  }
}
