<template lang="pug">
.row
    .col-lg-8
        form
            fieldset(:disabled="!basketItems.items.length")
                .row
                    .mb-3.col-md-6
                        InputUI(
                            v-model="personWithAddress.firstName"
                            label="First name")
                    .mb-3.col-md-6
                        InputUI(
                            v-model="personWithAddress.lastName"
                            label="Last name")
                .row
                    .mb-3.col-12
                        InputUI(
                            v-model="personWithAddress.address"
                            label="Address")
                .row
                    .mb-3.col-md-6
                        InputUI(
                            v-model="personWithAddress.email"
                            label="Email"
                            type="email")
                    .mb-3.col-md-6
                        InputUI(
                            v-model="personWithAddress.phone"
                            label="Contact phone"
                            type="tel")
                .row
                    .mb-3.col-12
                        ButtonWithLoading.btn.btn-primary.w-100(
                            :is-loading="false"
                            title="Booking now!")
    .col-lg-4.rounded-2.border.px-3.pt-3(v-if="basketItems.items.length")
        .d-flex.flex-row.gap-4.justify-content-between.pb-3
            .cols #[h5.text-primary Your booking items]
            .cols Total booking
                span.badge.bg-primary.p-2.ms-2
                    SvgIcon.mx-2(
                        type="mdi"
                        size="18"
                        :path="mdiBasket"
                    )
                    | {{ basketItems.items.length }}
        TransitionGroup.container-transition(
            tag="ul"
            name="list"
        )
            div.border-top.py-2(
                v-for="(item, index) in basketItems.items"
                :key="index"
                :data-index="index"
            )
                .d-flex.flex-row.gap-4.justify-content-between.mb-4
                    .cols.flex-fill
                        router-link.text-success(:to="{name: 'bookable', params: {id: item.bookableId}}") {{ item.title }}
                    .cols {{ item.days }} days
                    .cols.fw-bold {{ priceUsdFormat(item.total) }}
                .d-flex.flex-row.gap-4.justify-content-between.mb-2
                    .cols From {{ item.start }}
                    .cols To {{ item.end }}
                    .cols
                        button.btn.btn-sm.btn-outline-secondary(@click.prevent="removeFromBasket(item.bookableId)")
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
import { mdiBasket, mdiTrashCanOutline } from '@mdi/js'
import { computed, reactive } from 'vue'
import { useStore } from 'vuex'

import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import InputUI from '@/Components/UI/InputUI.vue'
import { dateAsLocaleString } from '@/Composable/useDateTime'
import { priceUsdFormat } from '@/Composable/useMoney'
import { bookingStateKey } from '@/store/Booking'
import type { ICalculateBookingInfoWithBookableTitle } from '@/Types/ICalculateBooking'

const store = useStore(bookingStateKey)

const personWithAddress = reactive({
    firstName: 'Ivan',
    lastName: 'Petrov',
    address: 'Canada, Toronto, First street',
    email: 'aaa@canada.ca',
    phone: ''
})

interface IBasketItem {
    bookableId: string,
    title: string,
    total: number,
    start: string | null,
    end: string | null,
    days: number,
}

interface IBasketTable {
    items: IBasketItem[],
    total: number,
}

const basketItems = computed<IBasketTable>(() => {
    const basketTable: IBasketTable = {
        total: 0,
        items: [],
    }

    store.getters.basket.forEach((item: ICalculateBookingInfoWithBookableTitle) => {
        const { regular: { days: rDays = 0 } = {}, weekend: { days: wDays  = 0 } = {} } = item.breakdown || {}

        basketTable.items.push({
            bookableId: item.bookableId,
            title: item.bookableTitle,
            total: item.totalPrice || 0,
            start: dateAsLocaleString(item.dateStart),
            end: dateAsLocaleString(item.dateEnd),
            days: rDays + wDays,
        })

        basketTable.total += item.totalPrice || 0
    })

    return basketTable
})

const removeFromBasket = (bookableId: string) => store.dispatch('removeFromBasket', bookableId)

</script>

<style scoped>
.container-transition {
    position: relative;
    padding: 0;
}
</style>
