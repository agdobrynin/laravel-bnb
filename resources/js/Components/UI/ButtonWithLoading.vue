<template lang="pug">
button(
    :disabled="isLoading"
)
    span(v-if="isLoading")
        span.spinner-grow.spinner-grow-sm
        |   {{ titleLoading }}
    span(v-else)
        | {{ title }}
</template>

<script setup lang="ts">

withDefaults(
    defineProps<{title: string, isLoading: boolean, titleLoading?: string}>(),
    {
        titleLoading: 'Waite please...'
    }
)
</script>

