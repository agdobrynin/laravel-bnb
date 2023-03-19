<template lang="pug">
div
    AlertDisplay.mx-auto.alert.alert-danger(v-if="apiError") {{ apiError }}
    div(v-else)
        PlaceholderCard(v-if="isLoading")
        div(v-else)
            div(v-if="isReviewExist")
                h3.text-center.text-success You already left reviewed for this booking
            div.row(v-else-if="bookable !== null && booking")
                .col-md-4
                    .card
                        .card-header.fs-5 State at {{ bookable?.category }}:
                            router-link(
                                :to="{name: 'bookable', params: {id: bookable?.id}}"
                                class="ms-3") {{ bookable?.title }}
                        .card-body
                            | booking from {{ booking?.start }} to {{ booking?.end }}
                .col-md-8
                    .alert.alert-danger(
                        v-for="(error, index) in validation('id')"
                        :key="`err_id_${index}`") Field "review key" has error: {{ error }}
                    .mb-3
                        label.form-label(for="rating") Set rating (1 is worst &mdash; 5 is best)
                        RatingItem.form-control.ps-3#rating(
                            v-model="review.rating"
                            :max-rating="5"
                            :icon-size="43"
                            :read-only="false"
                            :class="{'is-invalid': validation('rating').length}")
                        div.invalid-feedback(
                            v-for="(error, index) in validation('rating')"
                            :key="`rating_${index}`") {{ error }}
                    .mb-3
                        InputUI(
                            :disabled="true"
                            label="Review as user"
                            :class="{'is-invalid': validation('verify-email').length}"
                            :errors="validation('verify-email')"
                            :model-value="bookingReviewer"
                        )
                    .mb-3
                        TextareaUI(
                            v-model.trim="review.description"
                            label="Describe your experience with"
                            :errors="validation('description')")
                    ButtonWithLoading.btn.btn-primary.w-100(
                        :is-loading="isSending"
                        @click.prevent="doStore") Save
            div(v-else) Can not get info about booking
</template>

<script setup lang="ts">
import type { Ref } from 'vue'
import { computed, onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import InputUI from '@/Components/UI/InputUI.vue'
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import RatingItem from '@/Components/UI/RatingItem.vue'
import TextareaUI from '@/Components/UI/TextareaUI.vue'
import { useApiErrors } from '@/Composable/useApiErrors'
import HttpApiService from '@/Services/HttpApiService'
import { useAuthStore } from '@/stores/auth'
import type {
    IBookingByReviewKey,
    IBookingByReviewKeyBase,
    IBookingByReviewKeyBookableInfo
} from '@/Types/IBookingByReviewKey'
import type { IReviewItem } from '@/Types/IReviewExistItem'

const route = useRoute()
const router = useRouter()
const authStore =  useAuthStore()

const review: IReviewItem = reactive({
    id: '',
    description: '',
    rating: 5,
})

const isLoading: Ref<boolean> = ref(true)
const isSending: Ref<boolean> = ref(false)
const isReviewExist: Ref<boolean> = ref(false)
const bookingByReviewKey: Ref<IBookingByReviewKey | null> = ref(null)

const { apiError, validation, errors } = useApiErrors()

const booking = computed<IBookingByReviewKeyBase | null>(() => {
    if (bookingByReviewKey.value?.data) {
        return {
            id: bookingByReviewKey.value.data.id,
            start: new Date(bookingByReviewKey.value.data.start).toDateString(),
            end:  new Date(bookingByReviewKey.value.data.end).toDateString(),
            user: bookingByReviewKey.value?.data.user,
        }
    }

    return null
})

const bookingReviewer = computed<string>(() => {
    if (bookingByReviewKey.value?.data?.user?.name) {
        return `${bookingByReviewKey.value.data.user.name} (${bookingByReviewKey.value.data.user.email})`
    }

    return 'Anonymous'
})

const bookable = computed<IBookingByReviewKeyBookableInfo | null>(() => {
    if (bookingByReviewKey.value?.data?.bookable) {
        return {
            id: bookingByReviewKey.value?.data.bookable.id,
            title: bookingByReviewKey.value?.data.bookable.title,
            category: bookingByReviewKey.value?.data.bookable.category,
        }
    }

    return null
})

const doStore = async (): Promise<void> => {
    isSending.value = true
    errors(null)

    try {
        await new HttpApiService().storeReview(review)

        if (authStore.user?.newReviewCount) {
            authStore.user.newReviewCount --
        }

        await router.push({ name: 'bookable', params: { id: bookable.value?.id } })
    } catch (reason) {
        errors(reason)
    }

    isSending.value = false
}

onMounted(async (): Promise<void> => {
    const httpSrv = new HttpApiService()
    review.id = route.params.id as string

    try {
        const result = await httpSrv.getReview(review.id)
        isReviewExist.value = result as boolean

        if(!isReviewExist.value) {
            bookingByReviewKey.value = await httpSrv.getBookingByReviewKey(review.id) as IBookingByReviewKey
        }
    } catch (reason) {
        errors(reason)
    }

    isLoading.value = false

})
</script>
