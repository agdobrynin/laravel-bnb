<template lang="pug">
div
    div.row.justify-content-center.row-cols-1.row-cols-md-2.row-cols-lg-3.row-cols-xl-4.g-4(v-if="loading === false")
        div.alert.alert-danger(v-if="apiError") {{ apiError }}
        div(v-else-if="bookables.length === 0") Not found bookable objects.
        template(v-else)
            div.col.d-flex.align-items-stretch(
                v-for="bookable in bookables"
                :key="bookable.id"
            )
                BookableItem(:item="bookable")
    div.row.justify-content-center.row-cols-1.row-cols-md-2.g-4(v-else)
        div.col(
            v-for="index in Array(4)"
            :key="index"
        )
            PlaceholderCard
</template>

<script setup lang="ts">
import type { Ref } from 'vue'
import { computed, onMounted, ref } from 'vue'

import BookableItem from '@/Components/BookableList/BookableListItem.vue'
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import HttpService from '@/Services/HttpService'
import type { InterfaceApiError } from '@/Services/Interfaces/InterfaceApiError'
import type { IBookableList } from '@/Types/IBookableList'

const bookableList: Ref<IBookableList|null> = ref(null)
const loading: Ref<boolean> = ref(true)
const apiError: Ref<string|null> = ref(null)

const bookables = computed(() => bookableList.value?.data || [])

onMounted(() => {
    (new HttpService())
        .getBookables()
        .then(data => bookableList.value = data as IBookableList)
        .catch((reason: InterfaceApiError) => apiError.value = reason.backendMessage)
        .finally(() => loading.value = false)
})
</script>
