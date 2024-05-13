/* eslint-disable import/order */
import '@/@iconify/icons-bundle'
import App from '@/App.vue'
import layoutsPlugin from '@/plugins/layouts'
import Vue3Toasity from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'
import router from '@/router'
import '@core-scss/template/index.scss'
import '@styles/styles.scss'
import { createPinia } from 'pinia'
import { createApp } from 'vue'

import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import dateFormatter from './plugins/dateFormatter'


const googleMapApi = import.meta.env.VITE_GOOGLE_MAP_API


const script = document.createElement('script')

script.src = `https://maps.googleapis.com/maps/api/js?key=${googleMapApi}&libraries=places`
script.defer = true
script.async = true

document.head.appendChild(script)

loadFonts()

const toast_options = {}

// Create vue app
const app = createApp(App)

//app.prototype.$attachment_path = "http://127.0.0.1:8000/attachments/";
app.config.globalProperties.$attachment_path = "http://127.0.0.1:8000/attachments/"

// Use plugins
app.use(dateFormatter)
app.use(vuetify)
app.use(createPinia())
app.use(router)
app.use(layoutsPlugin)
app.component('VueDatePicker', VueDatePicker)
app.use(Vue3Toasity, toast_options)



// Mount vue app
app.mount('#app')

export { app }
