<template lang="pug">
div
    nav.navbar.bg-light.border-bottom.navbar-light
        div.container.container-fluid
            router-link.navbar-brand.me-auto(:to="{name: 'home'}") {{ appName }}
            router-link.nav-link(:to="{name: 'basket_and_checkout'}") Basket
                span.badge.bg-secondary.m-2(v-if="basket.length" ) {{ basket.length }}
    div.container.py-4.px-3.mx-auto
        router-view
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { onBeforeMount } from 'vue'

import { useBasketStore } from '@/stores/basket'
import { useBookingViewStore } from '@/stores/booking-view'
import { useCheckoutPersonStore } from '@/stores/checkout-person'

const appName = import.meta.env.VITE_APP_NAME || 'Booking BnB'
const basketStore = useBasketStore()

const { basket } = storeToRefs(basketStore)

onBeforeMount(async () => {
    // Restore last dates for booking check
    useBookingViewStore().restoreDateRangeFromStorage()
    // restore basket
    basketStore.restoreFromStorage()
    // restore person on checkout page
    useCheckoutPersonStore().restoreFromStorage()
})
</script>
