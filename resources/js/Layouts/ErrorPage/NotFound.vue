<template lang="pug">
AlertDisplay.alert.alert-danger Page not found
    p URI: {{ route.path }}
</template>

<script lang="ts" setup>
import { useRoute } from 'vue-router'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'

const route = useRoute()
</script>
