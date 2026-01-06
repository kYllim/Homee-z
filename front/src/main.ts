import './assets/main.css'
import './assets/fullcalendar-tailwind.css'
import clickOutside from '@/directives/clickOutside';


import { createPinia } from 'pinia'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

const app = createApp(App)

app.directive('click-outside', clickOutside)
app.use(router)
app.use(createPinia())

app.mount('#app')
