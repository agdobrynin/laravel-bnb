// import './bootstrap';
import '../css/app.css'
import '../scss/app.scss'

import { createApp } from 'vue'

import router from '@/router'
import { pinia } from '@/stores'

import App from './App.vue'

createApp(App)
    .use(router)
    .use(pinia)
    .mount('#app')
