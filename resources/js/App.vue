<template lang="pug">
div
    nav.navbar.bg-light.border-bottom.navbar-light
        div.container.container-fluid
            router-link.navbar-brand.me-auto(:to="{name: 'home'}") {{ appName }}
    div.container.py-4.px-3.mx-auto
        router-view
</template>

<script setup lang="ts">
import { onBeforeMount } from 'vue'
import { useStore } from 'vuex'

import { bookingStateKey } from '@/store/Booking'

const appName = import.meta.env.VITE_APP_NAME || 'Booking BnB'
const store = useStore(bookingStateKey)

onBeforeMount(async () => {
    // Restore last dates for booking check
    await store.dispatch('restoreLastSearchBookingDates')
})
</script>
