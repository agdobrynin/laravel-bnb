<template lang="pug">
div
    .alert.alert-success.fs-2.text-center
        SvgIcon.me-4(
            type="mdi"
            :path="mdiThumbUp"
            size="60"
        )
        | Your booking is accepted
    table.table.table-striped
        thead
            tr
                th Bookable object
                th Booking dates
                th Price
        tbody
            tr(
                v-for="(item, index) in successTable.items"
                :key="`success-item${index}`"
            )
                td
                    router-link(:to="{name: 'bookable', params: {id: item.bookableId}}") {{ item.bookableTitle }}
                td From {{ item.start }} to {{ item.end }}
                td {{ item.price }}
        tfoot
            tr.fw-bold
                td.text-end(colspan="2") Total
                td {{ successTable.total }}
</template>

<script setup lang="ts">
//@ts-ignore
import SvgIcon from '@jamescoyle/vue-icon'
import { mdiThumbUp } from '@mdi/js'
import { computed } from 'vue'

import { dateAsLocaleString } from '@/Composable/useDateTime'
import { priceUsdFormat } from '@/Composable/useMoney'
import type { ICheckoutSuccess, ICheckoutSuccessItem } from '@/Types/ICheckout'

const props = defineProps<{ data: ICheckoutSuccess }>()
interface ICheckoutSuccessTable {
    total: string,
    items: ICheckoutSuccessTableRow[]
}

interface ICheckoutSuccessTableRow {
    bookableId: string,
    bookableTitle: string,
    start: string,
    end: string,
    price: string,
}

const successTable = computed<ICheckoutSuccessTable>(() => {
    const table: ICheckoutSuccessTable = { total: '0', items: [] }
    let totalSum = 0

    table.items = props.data.data.reduce((acc, item: ICheckoutSuccessItem) => {
        acc.push({
            bookableId: item.bookable.id,
            bookableTitle: item.bookable.title,
            start: dateAsLocaleString(item.start) || item.start,
            end: dateAsLocaleString(item.end) || item.end,
            price: priceUsdFormat(item.price)
        })

        totalSum += item.price

        return acc
    }, [] as ICheckoutSuccessTableRow[])

    table.total = priceUsdFormat(totalSum)

    return table
})


</script>
