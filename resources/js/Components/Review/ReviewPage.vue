<template lang="pug">
div
    ApiErrorDisplay(v-if="apiError") {{ apiError }}
    div(v-else)
        PlaceholderCard(v-if="isLoading")
        div(v-else)
            div(v-if="isReviewExist")
                h3.text-center.text-success You already left reviewed for this booking
            div.row(v-else-if="bookable && booking" )
                .col-md-4
                    .card
                        .card-header.fs-5 State at
                            router-link(
                                :to="{name: 'bookable', params: {id: bookable.id}}"
                                class="ms-3") {{ bookable.title }}
                        .card-body
                            | booking from {{ booking.start }} to {{ booking.end }}
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
                            :class="{'is-invalid': errorRating.length}")
                        div.invalid-feedback(
                            v-for="(error, index) in errorRating"
                            :key="`rating_${index}`") {{ error }}
                    .mb-3.form-floating
                        textarea#description.form-control.description-field(
                            v-model="review.description"
                            :disabled="isSending"
                            :class="{'is-invalid': errorDescription.length}")
                        label.form-label(for="description") Describe your experience with
                        div.invalid-feedback(
                            v-for="(error, index) in errorDescription"
                            :key="`desc_${index}`") {{ error }}
                    ButtonWithLoading.btn.btn-primary.w-100(
                        title="Send"
                        :is-loading="isSending"
                        @click.prevent="storeReview")
            div(v-else) Can not get info about booking
</template>

<script setup lang="ts">
import type { Ref } from 'vue'
import { computed, onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

import ApiErrorDisplay from '@/Components/UI/ApiErrorDisplay.vue'
import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import RatingItem from '@/Components/UI/RatingItem.vue'
import { ApiValidationError } from '@/Services/ApiValidationError'
import HttpService from '@/Services/HttpService'
import type { InterfaceApiError } from '@/Services/Interfaces/InterfaceApiError'
import type { InterfaceApiValidationError } from '@/Services/Interfaces/InterfaceApiValidationError'
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
const validationError: Ref<InterfaceApiValidationError|null> = ref( null)

const validationErrors = (field: string) => validationError.value?.getErrorsByField(field) || []

const errorDescription = computed<string[]>(() => validationErrors('description'))
const errorRating = computed<string[]>(() => validationErrors('rating'))
const errorId = computed<string[]>(() => validationErrors('id'))


const booking = computed<IBookingByReviewKeyBase | null>(() => {
    if (bookingByReviewKey.value?.data) {
        return {
            id: bookingByReviewKey.value.data.id,
            start: new Date(bookingByReviewKey.value.data.start).toDateString(),
            end:  new Date(bookingByReviewKey.value.data.end).toDateString()
        }
    }

    return null

})

const bookable = computed<IBookingByReviewKeyBookableInfo | null>(() => {
    if (bookingByReviewKey.value?.data?.bookable) {
        return {
            id: bookingByReviewKey.value?.data.bookable.id,
            title: bookingByReviewKey.value?.data.bookable.title
        }
    }

    return null
})

const storeReview = () => {
    isSending.value = true
    apiError.value = null
    validationError.value = null

    new HttpService()
        .storeReview(review)
        .then((): void => {
            router.push({ name: 'bookable', params: { id: bookable.value?.id } })
        })
        .catch((reason: InterfaceApiValidationError|InterfaceApiError): void => {
            if(reason instanceof ApiValidationError) {
                validationError.value = reason
            } else {
                apiError.value = (reason as InterfaceApiError).backendMessage
            }
        })
        .finally((): void => {
            isSending.value = false
        })
}

onMounted(async () => {
    const httpSrv = new HttpService()
    review.id = route.params.id as string

    await httpSrv.getReview(review.id)
        .then((result): void => {
            isReviewExist.value = result as boolean
        })
        .catch((reason: InterfaceApiError): void => {
            apiError.value = (reason.backendResponse?.message || reason.requestErrorMessage)
        })
        .finally((): void => {
            isLoading.value = false
        })

    if(!isReviewExist.value) {
        isLoading.value = true

        httpSrv.getBookingByReviewKey(review.id)
            .then((result): void => {
                bookingByReviewKey.value = result as IBookingByReviewKey
            })
            .catch((reason: InterfaceApiError): void => {
                apiError.value = reason.backendResponse?.message || reason.requestErrorMessage
            })
            .finally((): void => {
                isLoading.value = false
            })
    }
})
</script>

<style scoped>
.description-field {
    height: 8rem;
}
</style>
