<template lang="pug">
div.card.w-100
    div.card-header
        h5.card-title {{ item.title }}
    div.card-body
        p.card-text {{ item.description }}
    div.card-footer.text-end
        router-link.btn.btn-primary(:to="{name: 'bookable', params: {id: item.id}}") View
</template>

<script setup lang="ts">
import type { IBookable } from '@/Types/IBookable'

interface Props extends IBookable{}
defineProps<{item: Props}>()
</script>
