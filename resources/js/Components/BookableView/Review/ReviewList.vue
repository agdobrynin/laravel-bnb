<template lang="pug">
div
    div(v-if="loading")
        PlaceholderCard(
            v-for="index in [1,2]"
            :key="`place-holder${index}`"
            class="mb-4")
    div(v-else)
        .alert.alert-danger(v-if="apiError")
            | #[h5 Review list error] {{ apiError }}
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
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import HttpService from '@/Services/HttpService'
import type { InterfaceApiError } from '@/Services/Interfaces/InterfaceApiError'
import type { IReviewCollection, IReviewExistItem } from '@/Types/IReviewExistItem'

const props = defineProps<{bookabledId: string}>()

const loading: Ref<boolean> = ref(true)
const apiError: Ref<string|null> = ref(null)
const reviewCollection: Ref<IReviewCollection | null> = ref(null)

const reviews: ComputedRef<IReviewExistItem[]> = computed(() => reviewCollection.value?.data || [])

onMounted(() => {
    new HttpService().getBookableReviews(props.bookabledId)
        .then(response => reviewCollection.value = response as IReviewCollection)
        .catch((error: InterfaceApiError) => apiError.value = error.backendMessage)
        .finally(() => loading.value = false)
})
</script>
