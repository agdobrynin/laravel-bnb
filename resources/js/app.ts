// import './bootstrap';
import '../css/app.css'
import '../scss/app.scss'

import { createApp } from 'vue'

import router from '@/router'
import storeBooking, { bookingStateKey } from '@/store/Booking'

import App from './App.vue'

createApp(App)
    .use(router)
    .use(storeBooking, bookingStateKey)
    .mount('#app')
