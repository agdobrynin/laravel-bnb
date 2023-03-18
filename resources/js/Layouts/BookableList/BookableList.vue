<template lang="pug">
div
    div.row.justify-content-center.row-cols-1.row-cols-md-2.g-4(v-if="isLoading" )
        div.col(
            v-for="index in 4"
            :key="index"
        )
            PlaceholderCard
    div.row.justify-content-center.row-cols-1.row-cols-md-2.row-cols-lg-3.row-cols-xl-4.g-4(v-else)
        AlertDisplay(v-if="apiError") {{ apiError }}
        div(v-else-if="!hasBookables && !hasFilters") Not found bookable objects.
        template(v-else)
            Transition
                .d-flex.align-items-center.text-primary(v-if="isLoadingFilters")
                    span Loading filters...
                    .spinner-border.spinner-border-sm.ms-auto
                span.border.rounded-2.p-2.w-100(v-else)
                    BookableFilters(
                        :categories="bookableCategories"
                        :filters="bookableFilters"
                        @change-filters="onChangeFilters")
                    Transition(name="slide-fade")
                        .alert.alert-danger.mt-2.mb-0(v-if="apiErrorCategories") Load bookable categories: {{ apiErrorCategories }}
            div.w-100.alert.alert-warning(v-if="!hasBookables" )
                | Not found bookable objects by filter.
            PaginationUI(
                v-if="paginatorData.lastPage > 1"
                :data="paginatorData"
                @change-page="onChangePage")
            div.col.d-flex.align-items-stretch(
                v-for="bookable in bookables"
                :key="bookable.id"
            )
                BookableItem(:item="bookable")
            PaginationUI(
                v-if="paginatorData.lastPage > 1"
                :data="paginatorData"
                @change-page="onChangePage")
</template>

<script setup lang="ts">
import { computed, onBeforeMount, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import PaginationUI from '@/Components/UI/PaginationUI.vue'
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import {
    useFiltersBuildQueryStringParamsInRouter,
    usePaginatorBuildQueryStringParamsInRouter,
    usePaginatorData
} from '@/Composable/usePaginatorData'
import BookableFilters from '@/Layouts/BookableList/Compoents/BookableFilters.vue'
import BookableItem from '@/Layouts/BookableList/Compoents/BookableListItem.vue'
import { ApiError } from '@/Services/ApiError'
import HttpApiService from '@/Services/HttpApiService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { IBookable } from '@/Types/IBookable'
import type { IBookableCategoriesResponse, IBookableCategoryItem } from '@/Types/IBookableCategoryItem'
import type { IBookableList } from '@/Types/IBookableList'
import type { IBookableListFilters } from '@/Types/IBookableListFilters'
import type { IPaginationData } from '@/Types/IPagination'

const route = useRoute()

const bookableList = ref<IBookableList | null>(null)
const isLoading = ref<boolean>(true)
const isLoadingFilters = ref<boolean>(false)
const apiError = ref<string | null>(null)
const apiErrorCategories = ref<string | null>(null)
const bookableFilters = ref<IBookableListFilters>({})
const bookableCategories = ref<IBookableCategoryItem[]>([])

const bookables = computed<IBookable[]>(() => bookableList.value?.data || [])
const paginatorData = computed<IPaginationData>(() => usePaginatorData(bookableList.value?.meta || null))
const hasFilters = computed<boolean>(() => Boolean(Object.values(bookableFilters.value).filter(v => !!v).length))
const hasBookables = computed<boolean>(() => Boolean(bookables.value.length))

const onChangePage = (page: number) => doLoadBookables(page)

const onChangeFilters = async (filters: IBookableListFilters) => {
    bookableFilters.value = filters
    await useFiltersBuildQueryStringParamsInRouter(filters)
    await doLoadBookables(1)
}

const doLoadBookables = async (page: number): Promise<void> => {
    isLoading.value = true

    try {
        bookableList.value = await new HttpApiService().getBookables(page, bookableFilters.value)
    } catch (reason) {
        const error = reason as ApiErrorInterface
        apiError.value = error.apiError?.message || error.requestError
    }

    await usePaginatorBuildQueryStringParamsInRouter(page)

    isLoading.value = false
}

onMounted(async () => {
    const { page = 1 } = route.query

    await doLoadBookables(Number(page))

    isLoadingFilters.value = true
    new HttpApiService().bookableCategories()
        .then((res: IBookableCategoriesResponse) => bookableCategories.value = res.data)
        .catch((err: ApiError) => {
            apiErrorCategories.value = err.apiError?.message || err.requestError
        })
        .finally(() => isLoadingFilters.value = false)
})

onBeforeMount(() => {
    const {
        bookableCategoryId, priceMin, priceMax, priceWeekendMin, priceWeekendMax
    } = route.query as IBookableListFilters

    bookableFilters.value = { bookableCategoryId, priceMin, priceMax, priceWeekendMin, priceWeekendMax }
})
</script>
