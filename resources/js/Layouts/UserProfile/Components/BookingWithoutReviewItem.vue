<template lang="pug">
.card.w-100
    .card-header
        h5.card-title
            router-link(:to="{name: 'bookable', params: { id: data.bookable.id }}") {{ data.bookable.title }}
        .text-muted {{ dateFormat(data.start) }} &map; {{ dateFormat(data.end) }}
    .card-body.fw-light
        .d-flex.justify-content-between
            .text-muted Price
            .flex-fill.border-bottom.mx-2
            .text-muted {{ priceUsdFormat(data.price) }}
    .card-footer.text-end
        router-link.btn.btn-outline-success(:to="{name: 'reviewPage', params: { id: data.reviewKey }}") Leave review now
</template>

<script lang="ts" setup>
import { dateFromString } from '@/Composable/useDateTime'
import { priceUsdFormat } from '@/Composable/useMoney'
import type { IBookingWithoutReviewByUser } from '@/Types/IBookingWithoutReviewByUserCollection'

defineProps<{data: IBookingWithoutReviewByUser}>()

const dateFormat = (date: string): string => dateFromString(date)?.toDateString() || '😥'
</script>
