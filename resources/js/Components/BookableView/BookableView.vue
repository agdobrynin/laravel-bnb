<template lang="pug">
div
    .alert.alert-danger(v-if="apiError")
        | #[h5 Bookable error] {{ apiError }}
    PlaceholderCard(v-if="loading")
    div(v-else)
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
import ReviewList from '@/Components/Review/ReviewList.vue'
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import HttpService from '@/Services/HttpService'
import type { InterfaceApiError } from '@/Services/Interfaces/InterfaceApiError'
import type { IBookable } from '@/Types/IBookable'
import type { IBookableItem } from '@/Types/IBookableItem'

const route = useRoute()
const id: string = route.params.id as string

const loading: Ref<boolean> = ref(true)
const bookableItem: Ref<IBookableItem | null> = ref(null)
const apiError: Ref<string|null> = ref(null)

const bookable: ComputedRef<IBookable | null> = computed(() => bookableItem.value?.data || null)

onMounted(() => {
    new HttpService().getBookable(id)
        .then(response => bookableItem.value = response as IBookableItem)
        .catch((error: InterfaceApiError) => apiError.value = error.backendMessage)
        .finally(() =>  loading.value = false)
})
</script>
