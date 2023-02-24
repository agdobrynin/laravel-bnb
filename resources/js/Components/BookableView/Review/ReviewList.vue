<template lang="pug">
div
    div(v-if="loading")
        PlaceholderCard(
            v-for="index in [1,2]"
            :key="`place-holder${index}`"
            class="mb-4")
    div(v-else)
        ApiErrorDisplay(v-if="apiError") {{ apiError }}
        div(v-else)
            h6.text-uppercase(v-if="reviews.length") Review List
            h6.text-uppercase(v-else) Review List is Empty
            ReviewItem.d-sm-none.d-md-block(
                    v-for="review in reviews"
                    :key="review.id"
                    :item="review")
</template>

<script lang="ts" setup>
import type { ComputedRef, Ref } from 'vue'
import { computed, defineProps, onMounted, ref } from 'vue'

import ReviewItem from '@/Components/BookableView/Review/ReviewItem.vue'
import ApiErrorDisplay from '@/Components/UI/ApiErrorDisplay.vue'
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import HttpService from '@/Services/HttpService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { IReviewCollection, IReviewExistItem } from '@/Types/IReviewExistItem'

const props = defineProps<{bookabledId: string}>()

const loading: Ref<boolean> = ref(true)
const apiError: Ref<string|null> = ref(null)
const reviewCollection: Ref<IReviewCollection | null> = ref(null)

const reviews: ComputedRef<IReviewExistItem[]> = computed(() => reviewCollection.value?.data || [])

onMounted(async () => {
    try {
        reviewCollection.value = await new HttpService().getBookableReviews(props.bookabledId)
    } catch (reason) {
        const error = reason as ApiErrorInterface
        apiError.value = error.backendMessage || error.requestErrorMessage
    }

    loading.value = false
 })
</script>
