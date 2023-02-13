import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'

import BookableList from '@/Components/BookableList/BookableList.vue'

const routes: RouteRecordRaw[] = [
    {
        name: 'home',
        path: '/',
        component: BookableList,
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
