import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'

import BasketAndCheckout from '@/Components/Basket/BasketAndCheckout.vue'
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
    },
    {
        name: 'basket_and_checkout',
        path: '/basket',
        component: BasketAndCheckout
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (to.hash) {
            return {
                el: to.hash,
                behavior: 'smooth',
            }
        }

        return { top: 0, behavior: 'smooth' }
    }
})

export default router
