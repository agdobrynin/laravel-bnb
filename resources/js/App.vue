<template lang="pug">
div
    nav.navbar.bg-light.border-bottom.navbar-light.navbar-expand-md
        div.container.container-fluid(@click="doClick")
            router-link.navbar-brand.me-auto(:to="{name: 'home'}") {{ appName }}
            button.navbar-toggler(
                type="button"
                @click.prevent="displayMenu = !displayMenu"
            )
                span.navbar-toggler-icon
            .collapse.navbar-collapse(:class="{'show': displayMenu}")
                ul.navbar-nav.ms-auto
                    li.nav-item(v-if="!user")
                        router-link.nav-link(:to="{name: 'login'}") Sign in
                    li.nav-item(v-if="user !== null")
                        router-link.nav-link(:to="{ name: 'home' }")
                            span.position-relative
                                SvgIcon(
                                    type="mdi"
                                    :path="mdiAccount"
                                    :class="[user.isVerified ? 'text-success' : 'text-danger']"
                                )
                                | {{ user.name }}
                    li.nav-item(v-if="user")
                        a.nav-link(
                            href="#"
                            @click.prevent="doLogout"
                        ) Logout
                    li.nav-item(v-if="!user")
                        router-link.nav-link(:to="{name: 'registration'}") Registration
                    li.nav-item
                        router-link.nav-link(:to="{name: 'basket_and_checkout'}") Basket
                            span.badge.bg-secondary.ms-2(v-if="basket.length" ) {{ basket.length }}
    div.container.py-4.px-3.mx-auto
        Transition
            AlertDisplay.alert.alert-warning.w-50.mx-auto(
                v-if="!isSendVerifyLink && user && !user.isVerified"
                :svg-icon="mdiInformationOutline"
            )
                .fs-5
                    p Your email not confirmed
                    p Please check your email box and confirm your account by verification link.
                    p.text-primary Resend confirmation link to your email #[router-link(:to="{name: 'resend_confirm_link'}") again].
        router-view
</template>

<script setup lang="ts">
//@ts-ignore
import SvgIcon from '@jamescoyle/vue-icon'
import { mdiAccount, mdiInformationOutline } from '@mdi/js'
import { storeToRefs } from 'pinia'
import { computed, onBeforeMount, ref } from 'vue'
import { useRouter } from 'vue-router'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import { HttpAuthService } from '@/Services/HttpAuthService'
import { useAuthStore } from '@/stores/auth'
import { useBasketStore } from '@/stores/basket'
import { useBookingViewStore } from '@/stores/booking-view'
import { useCheckoutPersonStore } from '@/stores/checkout-person'

const appName = import.meta.env.VITE_APP_NAME || 'Booking BnB'
const basketStore = useBasketStore()
const authStore = useAuthStore()
const router = useRouter()

const { basket } = storeToRefs(basketStore)
const { user } = storeToRefs(authStore)
const isLoading = ref<boolean>(false)
const displayMenu = ref<boolean>(false)

const isSendVerifyLink = computed(() => router.currentRoute.value.name === 'resend_confirm_link')

const srv = new HttpAuthService()
const doLogout = async () => {
    isLoading.value = true
    await srv.logout()
    authStore.user = null
    isLoading.value = false
    await router.push({ name: 'home' })
}

const doClick = (e: Event) => {
    const el: HTMLElement = e.target as HTMLElement
    if (el.tagName.toUpperCase() === 'A') {
        displayMenu.value = false
    }
}

onBeforeMount(async () => {
    isLoading.value = true

    // Restore last dates for booking check
    useBookingViewStore().restoreDateRangeFromStorage()
    // restore basket
    basketStore.restoreFromStorage()
    // restore person on checkout page
    useCheckoutPersonStore().restoreFromStorage()
    // fetch auth user from back
    await authStore.fetchUser()

    isLoading.value = false
})
</script>
