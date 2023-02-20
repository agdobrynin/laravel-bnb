import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'

import BookableList from '@/Components/BookableList/BookableList.vue'
import BookableView from '@/Components/BookableView/BookableView.vue'
import ReviewPage from '@/Components/Review/ReviewPage.vue'

const routes: RouteRecordRaw[] = [
    {
        name: 'home',
        path: '/',
        component: BookableList,
    },
    {
        name: 'bookable',
        path: '/bookable/:id',
        component: BookableView
    },
    {
        name: 'reviewPage',
        path: '/review/:id',
        component: ReviewPage
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
