<template lang="pug">
div
    nav.navbar.bg-light.border-bottom.navbar-light
        div.container.container-fluid
            router-link.navbar-brand.me-auto(:to="{name: 'home'}") {{ appName }}
            router-link.nav-link(:to="{name: 'home'}") Basket
                span.badge.bg-secondary.m-2(v-if="basketCount" ) {{ basketCount }}
    div.container.py-4.px-3.mx-auto
        router-view
</template>

<script setup lang="ts">
import { computed, onBeforeMount } from 'vue'
import { useStore } from 'vuex'

import { bookingStateKey } from '@/store/Booking'

const appName = import.meta.env.VITE_APP_NAME || 'Booking BnB'
const store = useStore(bookingStateKey)

const basketCount = computed<number>(() => store.getters.basketCount)

onBeforeMount(async () => {
    // Restore last dates for booking check
    await store.dispatch('restoreLastSearchBookingDates')
    // restore basket
    await store.dispatch('restoreBasket')
})
</script>
