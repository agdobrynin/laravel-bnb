<template lang="pug">
div
    ApiErrorDisplay(v-if="apiError") {{ apiError }}
    PlaceholderCard(v-if="loading")
    div(v-if="!apiError && !loading" )
        div.row
            div.col-md-8.mb-4
                div.card.mb-4
                    div.card-body
                        h2 {{ bookable.title }}
                        hr
                        article {{ bookable.description }}
                div.mb-4.mx-1
                    ReviewList(:bookabled-id="bookable.id")
            div.col-md-4.mb-4
                AvailabilityBooking(:id="bookable.id")
</template>

<script setup lang="ts">
import type { ComputedRef, Ref } from 'vue'
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

import AvailabilityBooking from '@/Components/BookableView/AvailabilityBooking.vue'
import ReviewList from '@/Components/BookableView/Review/ReviewList.vue'
import ApiErrorDisplay from '@/Components/UI/ApiErrorDisplay.vue'
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import HttpService from '@/Services/HttpService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { IBookable } from '@/Types/IBookable'
import type { IBookableItem } from '@/Types/IBookableItem'

const id: string = useRoute().params.id as string

const loading: Ref<boolean> = ref(true)
const bookableItem: Ref<IBookableItem | null> = ref(null)
const apiError: Ref<string|null> = ref(null)

const bookable: ComputedRef<IBookable | null> = computed(() => bookableItem.value?.data || null)

onMounted(async () => {
    try {
        bookableItem.value = await new HttpService().getBookable(id)
    } catch (reason) {
        const error = reason as ApiErrorInterface
        apiError.value = error.apiError?.message || error.requestError
    }

    loading.value = false
})
</script>
