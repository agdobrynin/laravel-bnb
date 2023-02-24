<template lang="pug">
div
    div.row.justify-content-center.row-cols-1.row-cols-md-2.g-4(v-if="loading" )
        div.col(
            v-for="index in Array(4)"
            :key="index"
        )
            PlaceholderCard
    div.row.justify-content-center.row-cols-1.row-cols-md-2.row-cols-lg-3.row-cols-xl-4.g-4(v-else)
        ApiErrorDisplay(v-if="apiError") {{ apiError }}
        div(v-else-if="bookables.length === 0") Not found bookable objects.
        template(v-else)
            div.col.d-flex.align-items-stretch(
                v-for="bookable in bookables"
                :key="bookable.id"
            )
                BookableItem(:item="bookable")
</template>

<script setup lang="ts">
import type { Ref } from 'vue'
import { computed, onMounted, ref } from 'vue'

import BookableItem from '@/Components/BookableList/BookableListItem.vue'
import ApiErrorDisplay from '@/Components/UI/ApiErrorDisplay.vue'
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import HttpService from '@/Services/HttpService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { IBookableList } from '@/Types/IBookableList'

const bookableList: Ref<IBookableList|null> = ref(null)
const loading: Ref<boolean> = ref(true)
const apiError: Ref<string|null> = ref(null)

const bookables = computed(() => bookableList.value?.data || [])

onMounted(async () => {
    try {
        bookableList.value = await new HttpService().getBookables()
    } catch (reason) {
        const error = reason as ApiErrorInterface
        apiError.value = error.apiError?.message || error.requestError
    }

    loading.value = false
})
</script>
