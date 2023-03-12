<template lang="pug">
div
    div.row.justify-content-center.row-cols-1.row-cols-md-2.g-4(v-if="loading" )
        div.col(
            v-for="index in 4"
            :key="index"
        )
            PlaceholderCard
    div.row.justify-content-center.row-cols-1.row-cols-md-2.row-cols-lg-3.row-cols-xl-4.g-4(v-else)
        AlertDisplay(v-if="apiError") {{ apiError }}
        div(v-else-if="bookables.length === 0") Not found bookable objects.
        template(v-else)
            PaginationUI(
                :data="paginatorData"
                @change-page="onChangePage")
            div.col.d-flex.align-items-stretch(
                v-for="bookable in bookables"
                :key="bookable.id"
            )
                BookableItem(:item="bookable")
            PaginationUI(
                :data="paginatorData"
                @change-page="onChangePage")
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

import BookableItem from '@/Components/BookableList/BookableListItem.vue'
import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import PaginationUI from '@/Components/UI/PaginationUI.vue'
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import { usePaginatorBuildQueryStringParamsInRouter, usePaginatorData } from '@/Composable/usePaginatorData'
import HttpApiService from '@/Services/HttpApiService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { IBookable } from '@/Types/IBookable'
import type { IBookableList } from '@/Types/IBookableList'
import type { IPaginationData } from '@/Types/IPagination'

const route = useRoute()

const bookableList = ref<IBookableList|null>(null)
const loading = ref<boolean>(true)
const apiError = ref<string|null>(null)

const bookables = computed<IBookable[]>(() => bookableList.value?.data || [])
const paginatorData = computed<IPaginationData>(() => usePaginatorData(bookableList.value?.meta || null))

const onChangePage = (page: number) => doLoadBookables(page)
const doLoadBookables = async (page: number): Promise<void> => {
    loading.value = true

    try {
        bookableList.value = await new HttpApiService().getBookables(page)
    } catch (reason) {
        const error = reason as ApiErrorInterface
        apiError.value = error.apiError?.message || error.requestError
    }

    await usePaginatorBuildQueryStringParamsInRouter(page)

    loading.value = false
}

onMounted(async () => {
    const { page = 1 } = route.query

    await doLoadBookables(Number(page))
})
</script>
