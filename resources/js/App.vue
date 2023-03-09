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
        router-view
</template>

<script setup lang="ts">
//@ts-ignore
import SvgIcon from '@jamescoyle/vue-icon'
import { mdiAccount } from '@mdi/js'
import { storeToRefs } from 'pinia'
import { onBeforeMount, ref } from 'vue'
import { useRouter } from 'vue-router'

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
