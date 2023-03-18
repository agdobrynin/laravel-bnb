import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'

import BookableList from '@/Layouts/BookableList/BookableList.vue'

const BookableView = () => import('@/Layouts/BookableView/BookableView.vue')
const ReviewPage = () => import('@/Layouts/Review/ReviewPage.vue')
const BasketAndCheckout = () => import('@/Layouts/Basket/BasketAndCheckout.vue')

const LoginUser = () => import('@/Layouts/Auth/LoginUser.vue')
const RegistrationUser = () => import('@/Layouts/Auth/RegistrationUser.vue')
const ResendConfirmLink = () => import('@/Layouts/Auth/ResendConfirmLink.vue')
const ForgotPassword = () => import('@/Layouts/Auth/ForgotPassword.vue')
const ResetPassword = () => import('@/Layouts/Auth/ResetPassword.vue')
const VerifyEmail = () => import('@/Layouts/Auth/VerifyEmail.vue')

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
    },
    {
        name: 'verify-email',
        path: '/verify-email/:id/:hash/:expires/:signature',
        component: VerifyEmail,
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
