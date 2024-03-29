import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'

import BookableList from '@/Layouts/BookableList/BookableList.vue'
import NotFound from '@/Layouts/ErrorPage/NotFound.vue'
import { useAuthStore } from '@/stores/auth'

const BookableView = () => import('@/Layouts/BookableView/BookableView.vue')
const ReviewPage = () => import('@/Layouts/Review/ReviewPage.vue')
const BasketAndCheckout = () => import('@/Layouts/Basket/BasketAndCheckout.vue')

const LoginUser = () => import('@/Layouts/Auth/LoginUser.vue')
const RegistrationUser = () => import('@/Layouts/Auth/RegistrationUser.vue')
const ResendConfirmLink = () => import('@/Layouts/Auth/ResendConfirmLink.vue')
const ForgotPassword = () => import('@/Layouts/Auth/ForgotPassword.vue')
const ResetPassword = () => import('@/Layouts/Auth/ResetPassword.vue')
const VerifyEmail = () => import('@/Layouts/Auth/VerifyEmail.vue')

const UserProfile = () => import('@/Layouts/UserProfile/UserProfile.vue')
const BookingWithoutReviews = () => import('@/Layouts/UserProfile/BookingWithoutReviews.vue')

type IProtectedRoute = {
    meta?: {
        isProtected?: boolean,
    }
}

type RouteRecordWithProtected = RouteRecordRaw & IProtectedRoute

const routes: RouteRecordWithProtected[] = [
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
        name: 'resend-confirm-link',
        path: '/resend-confirm-link',
        component: ResendConfirmLink,
        meta: { isProtected: true }
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
        meta: { isProtected: true }
    },
    {
        name: 'user-profile',
        path: '/user-profile',
        component: UserProfile,
        meta: { isProtected: true }
    },
    {
        name: 'booking-without-reviews',
        path: '/user-profile/booking-without-reviews',
        component: BookingWithoutReviews,
        meta: { isProtected: true },
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: () => NotFound,
    },
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
    },
})

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()

    if (to.meta.isProtected && authStore.user === null) {
        next({ name: 'login', query: { from: to.path } })
    } else {
        next()
    }
})

export default router
