<template lang="pug">
div
    nav.navbar.bg-light.border-bottom.navbar-light
        div.container.container-fluid
            router-link.navbar-brand.me-auto(:to="{name: 'home'}") {{ appName }}
    div.container.py-4.px-3.mx-auto
        router-view
</template>

<script setup lang="ts">
const appName = import.meta.env.VITE_APP_NAME || 'Booking BnB'
</script>
