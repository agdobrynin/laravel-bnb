<template lang="pug">
div
    div(v-if="loading")
        PlaceholderCard(
            v-for="index in 3"
            :key="`place-holder${index}`"
            class="mb-4")
    div(v-else)
        AlertDisplay(v-if="apiError") {{ apiError }}
        div.d-none.d-md-block(v-else)
            h6.text-uppercase(v-if="reviews.length === 0") Review List is Empty
            div(v-else)
                h5.text-uppercase Review List
                PaginationUI(
                    :data="paginatorData"
                    @change-page="onChangePage")
                ReviewItem(
                        v-for="review in reviews"
                        :key="review.id"
                        :item="review")
                PaginationUI(
                    :data="paginatorData"
                    @change-page="onChangePage")
</template>

<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

import ReviewItem from '@/Components/BookableView/Review/ReviewItem.vue'
import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import PaginationUI from '@/Components/UI/PaginationUI.vue'
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import { usePaginatorBuildQueryStringParamsInRouter, usePaginatorData } from '@/Composable/usePaginatorData'
import HttpApiService from '@/Services/HttpApiService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { IPaginationData } from '@/Types/IPagination'
import type { IReviewCollection, IReviewExistItem } from '@/Types/IReviewExistItem'

const props = defineProps<{bookabledId: string}>()

const loading = ref<boolean>(true)
const apiError = ref<string|null>(null)
const reviewCollection = ref<IReviewCollection | null>(null)
const route = useRoute()

const reviews = computed<IReviewExistItem[]>(() => reviewCollection.value?.data || [])

const paginatorData = computed<IPaginationData>(() => usePaginatorData(reviewCollection.value?.meta || null))

const loadReviews = async (page: number) => {
    loading.value = true

    try {
        reviewCollection.value = await new HttpApiService().getBookableReviews(props.bookabledId, page)
    } catch (reason) {
        const error = reason as ApiErrorInterface
        apiError.value = error.apiError?.message || error.requestError
    }

    await usePaginatorBuildQueryStringParamsInRouter(page)

    loading.value = false
}

const onChangePage = (page: number): void => {
    loadReviews(page)
}

onMounted(async () => {
    const { page = 1 } = route.query
    await loadReviews(page as number)
})
</script>
