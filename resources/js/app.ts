import './bootstrap';
import '../css/app.css'
import '../scss/app.scss'

import {createApp} from 'vue'
import App from './App.vue'
import router from '@/router'

createApp(App)
    .use(router)
    .mount('#app')
