<template lang="pug">
div
    div(v-if="loading")
        PlaceholderCard(
            v-for="index in 3"
            :key="`place-holder${index}`"
            class="mb-4")
    div(v-else)
        button.btn.btn-outline-secondary.w-100.d-md-none.d-block.text-uppercase.mb-4(
            @click.prevent="forceShowReviewList = !forceShowReviewList"
        )
            span(v-if="forceShowReviewList") Hide reviews
            span(v-else) Show reviews
        AlertDisplay(v-if="apiError") {{ apiError }}
        div.d-md-block(
            v-else
            :class="[forceShowReviewList ? 'd-block': 'd-none']"
        )
            h6.text-uppercase(v-if="reviews.length === 0") Review List is Empty
            div(v-else)
                h5.text-uppercase.d-none.d-md-block Review List
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

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import PaginationUI from '@/Components/UI/PaginationUI.vue'
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import { usePaginatorBuildQueryStringParamsInRouter, usePaginatorData } from '@/Composable/usePaginatorData'
import ReviewItem from '@/Layouts/BookableView/Componets/Review/ReviewItem.vue'
import HttpApiService from '@/Services/HttpApiService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { IPaginationData } from '@/Types/IPagination'
import type { IReviewCollection, IReviewExistItem } from '@/Types/IReviewExistItem'

const props = defineProps<{bookabledId: string}>()

const loading = ref<boolean>(true)
const apiError = ref<string|null>(null)
const reviewCollection = ref<IReviewCollection | null>(null)
const forceShowReviewList = ref<boolean>(false)

const route = useRoute()

const reviews = computed<IReviewExistItem[]>(() => reviewCollection.value?.data || [])

const paginatorData = computed<IPaginationData>(() => usePaginatorData(reviewCollection.value?.meta || null))

const loadReviews = async (page?: number) => {
    loading.value = true

    try {
        reviewCollection.value = await new HttpApiService().getBookableReviews(props.bookabledId, page)
    } catch (reason) {
        const error = reason as ApiErrorInterface
        apiError.value = error.apiError?.message || error.requestError
    }

    if (page) {
        await usePaginatorBuildQueryStringParamsInRouter(page)
    }

    loading.value = false
}

const onChangePage = (page: number): void => {
    loadReviews(page)
}

onMounted(async () => {
    const { page } = route.query
    await loadReviews(page ? Number(page): undefined)
})
</script>
