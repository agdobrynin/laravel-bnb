<template lang="pug">
.row
    .col-12(v-if="apiError")
        AlertDisplay.alert.alert-danger {{ apiError }}
    .col-lg-8(v-if="bookingAttempt")
        CheckoutSuccess(:data="bookingAttempt")
    .col-lg-8(v-else)
        form
            fieldset(:disabled="!checkoutForm.bookings.length || isLoading")
                .row
                    .mb-3.col-md-6
                        InputUI(
                            v-model="checkoutForm.person.firstName"
                            :errors="validation('person.first_name')"
                            label="First name")
                    .mb-3.col-md-6
                        InputUI(
                            v-model="checkoutForm.person.lastName"
                            :errors="validation('person.last_name')"
                            label="Last name")
                .row
                    .mb-3.col-12
                        InputUI(
                            v-model="checkoutForm.person.address"
                            :errors="validation('person.address')"
                            label="Address")
                .row
                    .mb-3.col-md-6
                        InputUI(
                            v-model="checkoutForm.person.email"
                            :errors="validation('person.email')"
                            :readonly="Boolean(authStore.user.value)"
                            label="Email"
                            type="email")
                    .mb-3.col-md-6
                        InputUI(
                            v-model="checkoutForm.person.phone"
                            :errors="validation('person.phone')"
                            label="Contact phone"
                            type="tel")
                .row
                    .mb-3.col-12
                        ButtonWithLoading.btn.btn-primary.w-100(
                            :is-loading="isLoading"
                            @click.prevent="checkout") Booking now!
    .col-lg-4.rounded-2.border.px-3.pt-3(v-if="basket.items.length")
        .d-flex.flex-row.gap-4.justify-content-between.pb-3
            .cols #[h5.text-primary Your booking items]
            .cols
                h5 Total booking
                    SvgIcon.mx-2(
                        type="mdi"
                        size="18"
                        :path="mdiBasket"
                    )
                    | {{ basket.total }}
        TransitionGroup.container-transition(
            tag="ul"
            name="list"
        )
            div.border-top.py-2(
                v-for="(item, index) in basket.items"
                :key="index"
                :data-index="index"
            )
                div.text-danger.mb-2.mx-3(
                    v-for="(error, errIndex) in validation(`bookings.${index}`)"
                    :key="`error_booking_${index}_${errIndex}`") {{ error }}
                div(:class="{'alert alert-danger':validation(`bookings.${index}`).length}")
                    .d-flex.flex-row.gap-4.justify-content-between.mb-4
                        .cols.flex-fill
                            router-link.text-success(:to="{name: 'bookable', params: {id: item.bookableId}}") {{ item.title }}
                        .cols {{ item.days }} days
                        .cols.fw-bold {{ priceUsdFormat(item.total) }}
                    .d-flex.flex-row.gap-4.justify-content-between.mb-2
                        .cols From {{ item.start }}
                        .cols To {{ item.end }}
                        .cols
                            button.btn.btn-sm.btn-outline-secondary(
                                :disabled="isLoading"
                                @click.prevent="removeFromBasket(item.bookableId)"
                            )
                                SvgIcon.me-2(
                                    type="mdi"
                                    size="24"
                                    :path="mdiTrashCanOutline"
                                )
                                | Remove
    .col-md-4.p-2.rounded-3.border(v-else)
        SvgIcon.mx-2(
            type="mdi"
            size="28"
            :path="mdiBasket"
        )
        | Basket is empty
</template>

<script lang="ts" setup>
//@ts-ignore
import SvgIcon from '@jamescoyle/vue-icon'
import { mdiBasket , mdiTrashCanOutline } from '@mdi/js'
import { storeToRefs } from 'pinia'
import { computed, reactive, ref } from 'vue'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import InputUI from '@/Components/UI/InputUI.vue'
import { useApiErrors } from '@/Composable/useApiErrors'
import { dateAsLocaleString } from '@/Composable/useDateTime'
import { priceUsdFormat } from '@/Composable/useMoney'
import CheckoutSuccess from '@/Layouts/Basket/Components/CheckoutSuccess.vue'
import HttpApiService from '@/Services/HttpApiService'
import { useAuthStore } from '@/stores/auth'
import { useBasketStore } from '@/stores/basket'
import { useCheckoutPersonStore } from '@/stores/checkout-person'
import type { ICalculateBookingInfoWithBookableTitle } from '@/Types/ICalculateBooking'
import type { IBasketTable, ICheckout, ICheckoutBookingItem } from '@/Types/ICheckout'
import type { ICheckoutSuccess } from '@/Types/ICheckout'

const basketStore = useBasketStore()
const checkoutPersonStore = useCheckoutPersonStore()
const authStore = storeToRefs(useAuthStore())
const { validation, apiError, errors, validationErrors } = useApiErrors()

const basket = computed<IBasketTable>(() => {
    const basketTable: IBasketTable = { total: '0', items: [] }
    let totalBasket = 0

    basketStore.basket.forEach((item: ICalculateBookingInfoWithBookableTitle) => {
        const { regular: { days: rDays = 0 } = {}, weekend: { days: wDays  = 0 } = {} } = item.breakdown || {}

        basketTable.items.push({
            bookableId: item.bookableId,
            title: item.bookableTitle,
            total: item.totalPrice || 0,
            start: dateAsLocaleString(item.dateStart),
            end: dateAsLocaleString(item.dateEnd),
            days: rDays + wDays,
        })

        totalBasket += item.totalPrice || 0
    })

    basketTable.total = priceUsdFormat(totalBasket)

    return basketTable
})

const bookings = computed<ICheckoutBookingItem[]>(() => {
    return basketStore.basket.reduce((acc: ICheckoutBookingItem[], item: ICalculateBookingInfoWithBookableTitle) => {
        acc.push({
            bookableId: item.bookableId,
            start: item.dateStart,
            end: item.dateEnd,
        })

        return acc
    } , [])
})

const checkoutForm: ICheckout = reactive({ person: checkoutPersonStore.person, bookings })

if (authStore.user.value?.email) {
    checkoutForm.person.email = authStore.user.value.email
    checkoutForm.person.firstName = authStore.user.value.name.split(' ')[0]
    checkoutForm.person.lastName = authStore.user.value.name.split(' ')[1]
}

const isLoading = ref<boolean>(false)
const bookingAttempt = ref<ICheckoutSuccess|null>(null)

const removeFromBasket = (bookableId: string): void => {
    validationErrors.value = null
    basketStore.removeFromBasket(bookableId)
}

const checkout = async () => {
    isLoading.value = true
    bookingAttempt.value = null
    errors(null)
    checkoutPersonStore.saveToStorage()

    try {
        bookingAttempt.value = await new HttpApiService().booking(checkoutForm)

        if (authStore.user.value !== null) {
            authStore.user.value.newReviewCount++
        }

        basketStore.emptyBasket()
    } catch (reason) {
        errors(reason)
    }

    isLoading.value = false
}
</script>

<style scoped>
.container-transition {
    position: relative;
    padding: 0;
}
</style>
