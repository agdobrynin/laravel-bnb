import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'

import BookableList from '@/Components/BookableList/BookableList.vue'

const BookableView = () => import('@/Components/BookableView/BookableView.vue')
const ReviewPage = () => import('@/Components/Review/ReviewPage.vue')
const BasketAndCheckout = () => import('@/Components/Basket/BasketAndCheckout.vue')
const Login = () => import('@/Components/Auth/Login.vue')
const Registration = () => import('@/Components/Auth/Registeration.vue')

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
    },
    {
        name: 'login',
        path: '/login',
        component: Login,
    },
    {
        name: 'registration',
        path: '/registration',
        component: Registration,
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
