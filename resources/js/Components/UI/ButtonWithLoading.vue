<template lang="pug">
button(
    :type="btnType"
    :disabled="isLoading"
)
    span(v-if="isLoading")
        span.spinner-grow.spinner-grow-sm
        |   {{ titleLoading }}
    span(v-else)
        slot
</template>

<script setup lang="ts">

type buttonTypes = 'button' | 'submit' | 'reset'

withDefaults(
    defineProps<{isLoading?: boolean, titleLoading?: string, btnType?: buttonTypes}>(),
    {
        titleLoading: 'Waite please...',
        isLoading: false,
        btnType: 'button'
    }
)
</script>

