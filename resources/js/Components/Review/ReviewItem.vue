<template lang="pug">
.border-bottom.mb-4
    .row.mb-4
        div.col-md-6 {{ item.name }}
        div.col-md-6.d-md-flex.justify-content-end(:title="item.rating")
            span(
                v-for="star in stars"
                :key="`star-${star}`") 🟩
            span ⬛
            span.ps-2 {{ item.rating }}
    .row
        .col-md-12.text-muted was added {{ timeAgo }}
    .row
        .col-md-12.mb-4 {{ item.description }}
</template>

<script setup lang="ts">
import { format } from 'timeago.js'
import { computed, defineProps } from 'vue'

import type { IReviewItem } from '@/Types/IReviewItem'

interface IProps extends IReviewItem{}

const props = defineProps<{item: IProps}>()

const stars = computed(() => [...Array(Math.round(props.item.rating)).keys()])
const timeAgo = format(props.item.createdAt)
</script>

