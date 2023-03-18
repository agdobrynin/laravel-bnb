<template lang="pug">
div
    AlertDisplay(v-if="apiError") {{ apiError }}
    PlaceholderCard(v-if="loading")
    div(v-if="!apiError && !loading" )
        .row
            .col-md-8.mb-4
                .card.mb-4
                    .card-header #[h2 {{ bookable?.category }}: {{ bookable?.title }}]
                    .card-body
                        article {{ bookable?.description }}
                    .card-footer
                        .d-flex.justify-content-between
                            .text-muted Regular price per day
                            .flex-fill.border-bottom.mx-2
                            .text-muted {{ prices.price }} per day
                        .d-flex.justify-content-between
                            .text-muted Weekend price per day
                            .flex-fill.border-bottom.mx-2
                            .text-muted {{ prices.price_weekend }} per day
                ReviewList(:bookabled-id="bookable.id")
            .col-md-4.mb-4
                AvailabilityBooking(
                    :id="bookable.id"
                    @is-availability="checkPrice")
                AlertDisplay(
                    v-if="calculatePriceError"
                    :icon-size="40")
                        div.alert.alert-danger.fs-6(
                            v-for="(error, index) in calculatePriceError"
                            :key="`calc-price-${index}`") {{ error }}
                Transition(name="bounce")
                    PriceBreakdown.mt-4(
                        v-if="calculate"
                        :calculate-booking="calculate")
                        template(#header) Checked booking price
                Transition.mt-4(name="bounce")
                    ButtonWithLoading.btn.btn-outline-success.mt-4.w-100(
                        v-if="calculate && !inBasket"
                        :is-loading="false"
                        @click.prevent="addToBasket") Booking now
                Transition.mt-4
                    .alert.alert-warning.text-center(v-if="inBasket")
                        p &laquo;#[b {{ bookable.title }}]&raquo; already in basket.
                        p If you want change dates,&nbsp;
                            a.link(
                                role="button"
                                @click.prevent="removeFromBasket") remove from the basket first.
                Transition.mt-4
                    PriceBreakdown.mt-4.text-center(
                        v-if="inBasket"
                        :calculate-booking="inBasket")
                        template(#header) #[div.alert.alert-warning Booking {{ bookingDates }}]
                Transition
                    router-link.btn.btn-outline-secondary.w-100(
                        v-if="inBasket"
                        :to="{name: 'basket_and_checkout'}"
                        ) Go to basket
</template>

<script setup lang="ts">
import type { Ref } from 'vue'
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import { dateAsLocaleString } from '@/Composable/useDateTime'
import { priceUsdFormat } from '@/Composable/useMoney'
import AvailabilityBooking from '@/Layouts/BookableView/Componets/AvailabilityBooking.vue'
import PriceBreakdown from '@/Layouts/BookableView/Componets/PriceBreakdown.vue'
import ReviewList from '@/Layouts/BookableView/Componets/ReviewList.vue'
import { ApiError } from '@/Services/ApiError'
import { ApiValidationError } from '@/Services/ApiValidationError'
import HttpApiService from '@/Services/HttpApiService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { ApiValidationErrorInterface } from '@/Services/Interfaces/ApiValidationErrorInterface'
import { useBasketStore } from '@/stores/basket'
import type { IBookable } from '@/Types/IBookable'
import type { IBookableItem } from '@/Types/IBookableItem'
import type { IBookingDates } from '@/Types/IBookingAvailability'
import type { ICalculateBooking, ICalculateBookingInfo } from '@/Types/ICalculateBooking'
import type  { ICalculateBookingInfoWithBookableTitle } from '@/Types/ICalculateBooking'

const id: string = useRoute().params.id as string
const store = useBasketStore()

const loading: Ref<boolean> = ref(true)
const bookableItem: Ref<IBookableItem | null> = ref(null)
const apiError: Ref<string|null> = ref(null)
const calculate: Ref<ICalculateBookingInfo|null> = ref(null)
const calculatePriceError: Ref<string[]| null> = ref(null)

const bookable = computed<IBookable | null>(() => bookableItem.value?.data || null)
const prices = computed(() => {
    return {
        price: priceUsdFormat(bookable.value?.price || 0),
        price_weekend: priceUsdFormat(bookable.value?.price_weekend || 0),
    }
})

const inBasket = computed<ICalculateBookingInfoWithBookableTitle | undefined>(() => {
    return bookable.value ? store.inBasket(bookable.value.id) : undefined
})

const bookingDates = computed<string>(() => {
    if (inBasket.value !== undefined) {
            return `from ${dateAsLocaleString(inBasket.value.dateStart)} to ${dateAsLocaleString(inBasket.value.dateEnd)}`
    }

    return ''
})

const checkPrice = async (isAvailable: IBookingDates | undefined): Promise<void> => {
    calculatePriceError.value = null
    calculate.value = null

    if (isAvailable && bookable.value?.id) {
        try {
            const calculateResponse: ICalculateBooking = await new HttpApiService()
                .calculateBooking(bookable.value.id, isAvailable.start, isAvailable.end)
            calculate.value = calculateResponse.data.calculate
        } catch (reason) {
            const error = reason as Error | ApiErrorInterface | ApiValidationErrorInterface
            if (error instanceof ApiValidationError) {
                calculatePriceError.value = error.validationErrors
            } else if (error instanceof ApiError) {
                calculatePriceError.value = [error.apiError?.message || error.requestError]
            } else {
                calculatePriceError.value = [(error as Error).message]
            }
        }
    }
}

const addToBasket = (): void => {
    if (null !== calculate.value && bookable.value?.id) {
        const bookableTitle: string = `${bookable.value.category}: ${bookable.value.title}`

        const payload:  ICalculateBookingInfoWithBookableTitle = {
            ...calculate.value, ... { bookableTitle }
        }

        store.addToBasket(payload)
    }
}

const removeFromBasket = (): void => {
    if (bookable.value?.id) {
        store.removeFromBasket(bookable.value.id)
    }
}

onMounted(async () => {
    try {
        bookableItem.value = await new HttpApiService().getBookable(id)
    } catch (reason) {
        const error = reason as ApiErrorInterface
        apiError.value = error.apiError?.message || error.requestError
    }

    loading.value = false
})
</script>
