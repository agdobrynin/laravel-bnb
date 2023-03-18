<template lang="pug">
.border-bottom.mb-4
    .row.mb-4
        div.col-md-6 {{ name }}
        div.col-md-6.d-md-flex.justify-content-end
            RatingItem(
                :model-value="item.rating"
                :max-rating="5"
                :icon-size="26")
    .row
        .col-md-12.text-muted was added {{ timeFormatted }}
    .row
        .col-md-12.mb-4 {{ item.description }}
</template>

<script setup lang="ts">
import { computed } from 'vue'

import RatingItem from '@/Components/UI/RatingItem.vue'
import { timeAgo } from '@/Composable/useDateTime'
import type { IReviewExistItem } from '@/Types/IReviewExistItem'

interface IProps extends IReviewExistItem{}

const props = defineProps<{item: IProps}>()

const timeFormatted = computed<string>(() => timeAgo(props.item.createdAt))
const name = computed<string>(() => props.item.user?.name || 'Anonymous')
</script>
