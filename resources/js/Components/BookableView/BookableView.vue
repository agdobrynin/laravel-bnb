<template lang="pug">
div
    PlaceholderCard(v-if="loading")
    div.alert.alert-danger(v-else-if="apiError")
        | {{ apiError }}
    div(v-else)
        div.row
            div.col-md-8.mb-4
                div.card.mb-4
                    div.card-body
                        h2 {{ bookable.title }}
                        hr
                        article {{ bookable.description }}
                div.mb-4.mx-1
                    h6.text-uppercase(v-if="reviews.length") Review List
                    ReviewItem.d-sm-none.d-md-block(
                        v-for="(review, index) in reviews"
                        :key="`review-${index}`"
                        :item="review"
                    )
            div.col-md-4.mb-4
                AvailabilityBooking(:id="bookable.id")
</template>

<script setup lang="ts">
import type { ComputedRef, Ref } from 'vue'
import { computed, ref } from 'vue'
import { useRoute } from 'vue-router'

import AvailabilityBooking from '@/Components/BookableView/AvailabilityBooking.vue'
import PlaceholderCard from '@/Components/PlaceholderCard/PlaceholderCard.vue'
import ReviewItem from '@/Components/Review/ReviewItem.vue'
import HttpService from '@/Services/HttpService'
import type { InterfaceApiError } from '@/Services/Interfaces/InterfaceApiError'
import type { IBookable } from '@/Types/IBookable'
import type { IBookableItem } from '@/Types/IBookableItem'
import type { IReviewItem } from '@/Types/IReviewItem'

const route = useRoute()
const id: string = route.params.id as string

const loading: Ref<boolean> = ref(true)
const bookableItem: Ref<IBookableItem|null> = ref(null)
const apiError: Ref<string|null> = ref(null)

const bookable: ComputedRef<IBookable| null> = computed(() => bookableItem.value?.data || null)

new HttpService()
    .getBookable(id)
    .then((response) => bookableItem.value = response as IBookableItem)
    .catch((error: InterfaceApiError) => apiError.value = error.backendMessage)
    .finally(() => loading.value = false)

const reviews: IReviewItem[] = [
    { name: 'Alex D', description: 'Professionally engage real-time innovation rather than performance.', rating: 4.2, createdAt: '2023-02-18T12:05' },
    { name: 'Ivan Petrov', description: 'Appropriately aggregate focused niche markets via', rating: 3.2, createdAt: '2023-02-15T22:09' },
    { name: 'Peter Jura', description: 'Efficiently create pandemic ROI whereas orthogonal models. Progressively generate market-driven models and parallel channels.', rating: 4.6, createdAt: '2019-12-06T16:32' },
]
</script>
