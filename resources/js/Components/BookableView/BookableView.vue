<template lang="pug">
div
    PlaceholderCard(v-if="loading")
    div.alert.alert-danger(v-else-if="apiError")
        | {{ apiError }}
    div(v-else)
        div.row
            div.col-md-8.mb-4
                div.card
                    div.card-body
                        h2 {{ bookable.title }}
                        hr
                        article {{ bookable.description }}
            div.col-md-4.mb-4
                Availability
</template>

<script setup lang="ts">
import type { ComputedRef, Ref } from 'vue'
import { computed, ref } from 'vue'
import { useRoute } from 'vue-router'

import Availability from '@/Components/BookableView/Availability.vue'
import PlaceholderCard from '@/Components/PlaceholderCard/PlaceholderCard.vue'
import HttpService from '@/Services/HttpService'
import type { InterfaceApiError } from '@/Services/InterfaceApiError'
import type { IBookable } from '@/Types/IBookable'
import type { IBookableItem } from '@/Types/IBookableItem'

const route = useRoute()
const id: string = route.params.id as string

const loading: Ref<boolean> = ref(true)
const bookableItem: Ref<IBookableItem|null> = ref(null)
const apiError: Ref<string|null> = ref(null)

const bookable: ComputedRef<IBookable| null> = computed(() => bookableItem.value?.data || null)

new HttpService()
    .getBookable(id)
    .then((response) => bookableItem.value = response as IBookableItem)
    .catch((error: InterfaceApiError) => apiError.value = error.backendMessage)
    .finally(() => loading.value = false)
</script>
