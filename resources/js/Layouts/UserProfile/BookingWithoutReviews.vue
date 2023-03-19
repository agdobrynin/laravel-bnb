<template lang="pug">
Transition
    PlaceholderCard(v-if="isLoading")
    div(v-else)
        AlertDisplay.alert.alert-danger(v-if="apiError") {{ apiError }}
        PaginationUI(
            v-if="paginatorData.lastPage > 1"
            :data="paginatorData"
            @change-page="onChangePage")
        .row.justify-content-center.row-cols-1.row-cols-md-2.row-cols-lg-3.row-cols-xl-4.g-4
            .col.d-flex.align-items-stretch(
                v-for="booking in bookingList"
                :key="booking.reviewKey"
            )
                BookingWithoutReviewItem(:data="booking")
</template>

<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import PaginationUI from '@/Components/UI/PaginationUI.vue'
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import { useApiErrors } from '@/Composable/useApiErrors'
import { usePaginatorBuildQueryStringParamsInRouter, usePaginatorData } from '@/Composable/usePaginatorData'
import BookingWithoutReviewItem from '@/Layouts/UserProfile/Components/BookingWithoutReviewItem.vue'
import HttpApiService from '@/Services/HttpApiService'
import type { IBookingWithoutReviewByUserCollection } from '@/Types/IBookingWithoutReviewByUserCollection'
import type { IBookingWithoutReviewByUser } from '@/Types/IBookingWithoutReviewByUserCollection'
import type { IPaginationData } from '@/Types/IPagination'

const route = useRoute()
const { errors, apiError } = useApiErrors()

const isLoading = ref<boolean>(true)
const bookingWithoutReview = ref<IBookingWithoutReviewByUserCollection | null>(null)

const paginatorData = computed<IPaginationData>(() => usePaginatorData(bookingWithoutReview.value?.meta || null))
const bookingList = computed<IBookingWithoutReviewByUser[]>(() => bookingWithoutReview.value?.data || [])

const doLoadList = async (page: number = 1): Promise<void> => {
    errors(null)
    isLoading.value = true

    try {
        bookingWithoutReview.value = await new HttpApiService().bookingWithoutReviewByUser(page)
    } catch (e) {
        errors(e)
    }

    await usePaginatorBuildQueryStringParamsInRouter(page)
    isLoading.value = false
}

const onChangePage = async (page: number) => await doLoadList(page)

onMounted(async () => {
    const { page = 1 } = route.query
    await doLoadList(Number(page))
})
</script>

