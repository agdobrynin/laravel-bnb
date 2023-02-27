<template lang="pug">
div
    h6.text-uppercase.text-secondary price breakdown
    table.table.table-striped.table-bordered
        tbody
            tr.text-center(
                v-for="(item, index) in formattedTable"
                :key="`breakdown-${index}`"
            )
                td(:class="{'fw-bold': lastRow === index}") {{ item.title }}
                td(:class="{'fw-bold': lastRow === index}")
                    span(v-if="!(lastRow === index)" ) {{ item.days }} &times; {{ item.price }}
                    span(v-else) {{ item.days }}
                td(:class="{'fw-bold': lastRow === index}") {{ item.total }}
</template>

<script lang="ts" setup>
import { computed } from 'vue'

import { priceUsdFormat } from '@/Composable/useMoney'
import type { ICalculateBooking, ICalculateBreakdownItem } from '@/Types/ICalculateBooking'

const props = defineProps<{calculateBooking: ICalculateBooking}>()

const regularPrice = computed<ICalculateBreakdownItem | undefined>(() => props.calculateBooking?.data?.breakdown?.regular)
const weekendPrice = computed<ICalculateBreakdownItem | undefined>(() => props.calculateBooking?.data?.breakdown?.weekend)
interface IFormattedTable {
    title: string,
    days: string,
    price: string,
    total: string,
}

const formattedTable = computed<IFormattedTable[]>(() => {
    const formattedTable: IFormattedTable[] = []
    let totalDays: number = 0
    let totalPrice: number = 0

    const { days: daysRegular, pricePerDay: pricePerDayRegular } = regularPrice.value || {}

    if (daysRegular && pricePerDayRegular) {
        formattedTable.push(makeItem('Regular days', daysRegular, pricePerDayRegular))
        totalDays +=daysRegular
        totalPrice += (daysRegular * pricePerDayRegular)
    }

    const { days: daysWeekend, pricePerDay: pricePerDayWeekend } = weekendPrice.value || {}

    if (daysWeekend && pricePerDayWeekend) {
        formattedTable.push(makeItem('Weekend days', daysWeekend, pricePerDayWeekend))
        totalDays +=daysWeekend
        totalPrice += (daysWeekend * pricePerDayWeekend)
    }

    if (totalDays && totalPrice) {
        formattedTable.push(makeItem('Total', totalDays, totalPrice))
    }

    return formattedTable
})

const lastRow = computed<number>(() => formattedTable.value.length -1)

const makeItem = (title: string, days: number, price: number): IFormattedTable => {
    return {
        title,
        days: `${days} days`,
        price: priceUsdFormat(price),
        total: priceUsdFormat(days * price)
    }
}
</script>
