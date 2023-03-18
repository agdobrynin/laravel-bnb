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
                        v-for="(error, index) in errorId"
                        :key="`err_id_${index}`") Field "review key" has error: {{ error }}
                    .mb-3
                        label.form-label(for="rating") Set rating (1 is worst &mdash; 5 is best)
                        RatingItem.form-control.ps-3#rating(
                            v-model="review.rating"
                            :max-rating="5"
                            :icon-size="43"
                            :read-only="false"
                            :class="{'is-invalid': errorRating.length}")
                        div.invalid-feedback(
                            v-for="(error, index) in errorRating"
                            :key="`rating_${index}`") {{ error }}
                    .mb-3
                        InputUI(
                            :disabled="true"
                            label="Review as user"
                            :class="{'is-invalid': errorVerifyEmail.length}"
                            :errors="errorVerifyEmail"
                            :model-value="bookingReviewer"
                        )
                    .mb-3
                        TextareaUI(
                            v-model.trim="review.description"
                            label="Describe your experience with"
                            :errors="errorDescription")
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
import { ApiError } from '@/Services/ApiError'
import { ApiValidationError } from '@/Services/ApiValidationError'
import HttpApiService from '@/Services/HttpApiService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { ApiValidationErrorInterface } from '@/Services/Interfaces/ApiValidationErrorInterface'
import type {
    IBookingByReviewKey,
    IBookingByReviewKeyBase,
    IBookingByReviewKeyBookableInfo
} from '@/Types/IBookingByReviewKey'
import type { IReviewItem } from '@/Types/IReviewExistItem'


const route = useRoute()
const router = useRouter()

const review: IReviewItem = reactive({
    id: '',
    description: '',
    rating: 5,
})

const isLoading: Ref<boolean> = ref(true)
const isSending: Ref<boolean> = ref(false)
const isReviewExist: Ref<boolean> = ref(false)
const bookingByReviewKey: Ref<IBookingByReviewKey | null> = ref(null)

const apiError: Ref<string | null> = ref(null)
const validationError: Ref<ApiValidationErrorInterface|null> = ref( null)

const validationErrors = (field: string) => validationError.value?.getErrorsByField(field) || []

const errorDescription = computed<string[]>(() => validationErrors('description'))
const errorRating = computed<string[]>(() => validationErrors('rating'))
const errorVerifyEmail = computed<string[]>(() => validationErrors('verify-email'))
const errorId = computed<string[]>(() => validationErrors('id'))

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
    apiError.value = null
    validationError.value = null

    try {
        await new HttpApiService().storeReview(review)
        await router.push({ name: 'bookable', params: { id: bookable.value?.id } })
    } catch (reason) {
        const error = reason as Error | ApiErrorInterface | ApiValidationErrorInterface

        if (error instanceof ApiValidationError) {
            validationError.value = error
        } else if (error instanceof ApiError) {
            apiError.value = error.apiError?.message || error.requestError
        } else {
            apiError.value = (error as Error).message
        }
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
        const error = reason as ApiErrorInterface
        apiError.value = (error.apiError?.message || error.requestError)
    }

    isLoading.value = false

})
</script>
