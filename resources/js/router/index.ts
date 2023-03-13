import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'

import BookableList from '@/Components/BookableList/BookableList.vue'

const BookableView = () => import('@/Components/BookableView/BookableView.vue')
const ReviewPage = () => import('@/Components/Review/ReviewPage.vue')
const BasketAndCheckout = () => import('@/Components/Basket/BasketAndCheckout.vue')
const LoginUser = () => import('@/Components/Auth/LoginUser.vue')
const RegistrationUser = () => import('@/Components/Auth/RegistrationUser.vue')

const ResendConfirmLink = () => import('@/Components/Auth/ResendConfirmLink.vue')
const ForgotPassword = () => import('@/Components/Auth/ForgotPassword.vue')
const ResetPassword = () => import('@/Components/Auth/ResetPassword.vue')

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
        component: LoginUser,
    },
    {
        name: 'registration',
        path: '/registration',
        component: RegistrationUser,
    },
    {
        name: 'resend_confirm_link',
        path: '/resend-confirm-link',
        component: ResendConfirmLink,
    },
    {
        name: 'forgot-password',
        path: '/forgot-password',
        component: ForgotPassword,
    },
    {
        name: 'reset-password',
        path: '/reset-password',
        component: ResetPassword,
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
