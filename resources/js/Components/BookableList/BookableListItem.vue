<template lang="pug">
.card.w-100
    .card-header
        h5.card-title {{ item.title }}
    .card-body
        p.card-text {{ item.description }}
    .card-body.fw-light
        .d-flex.justify-content-between
            .text-muted Regular price per day
            .flex-fill.border-bottom.mx-2
            .text-muted {{ prices.price }} per day
        .d-flex.justify-content-between
            .text-muted Weekend price per day
            .flex-fill.border-bottom.mx-2
            .text-muted {{ prices.price_weekend }} per day
    .card-footer.text-end
        router-link.btn.btn-primary(:to="{name: 'bookable', params: {id: item.id}}") View
</template>

<script setup lang="ts">
import { computed } from 'vue'

import { priceUsdFormat } from '@/Composable/useMoney'
import type { IBookable } from '@/Types/IBookable'

interface Props extends IBookable{}
const props = defineProps<{item: Props}>()

const prices = computed(() => {
    return { price: priceUsdFormat(props.item.price), price_weekend: priceUsdFormat(props.item.price_weekend) }
})
</script>
