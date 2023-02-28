<template lang="pug">
div
    h6.text-uppercase.text-secondary(v-if="$slots.header" )
        slot(name="header")
    table.table.table-striped.table-bordered
        tbody
            tr.text-center(
                v-for="(item, index) in table"
                :key="`breakdown-${index}`"
            )
                td {{ item.title }}
                td {{ item.days }} &times; {{ item.pricePerDay }}
                td {{ item.total }}
        tfoot(v-if="table.length")
            tr
                td.fw-bolder.text-end(colspan="2") Total
                td.fw-bolder.text-center {{ totalPrice }}
</template>

<script lang="ts" setup>
import { computed } from 'vue'

import { priceUsdFormat } from '@/Composable/useMoney'
import type { ICalculateBookingInfo, ICalculateBreakdownItem } from '@/Types/ICalculateBooking'
import { BreakdownPriceEnum } from '@/Types/ICalculateBooking'

const props = defineProps<{calculateBooking: ICalculateBookingInfo}>()

const totalPrice = computed<string>(() => priceUsdFormat(props.calculateBooking.totalPrice || 0))

const titleBreakdown = (item: BreakdownPriceEnum) => {
    const titles = {
        [BreakdownPriceEnum.REGULAR]: 'Regular days',
        [BreakdownPriceEnum.WEEKEND]: 'Weekend days',
    }

    return titles[item] || 'Unknown days'
}

const table = computed<IFormattedTable[]>(() => {
    return Object.entries(props.calculateBooking.breakdown || {})
        .reduce((acc: IFormattedTable[], [breakdownPrice, val]) => {
            const item: ICalculateBreakdownItem = val
            const title = titleBreakdown(breakdownPrice as BreakdownPriceEnum)

            acc.push(makeItem(title, item.days, item.pricePerDay, item.totalPrice))

            return acc
        }, [])
})

interface IFormattedTable {
    title: string,
    days: string,
    pricePerDay: string,
    total: string,
}


const makeItem = (title: string, days: number, pricePerDay: number, total: number): IFormattedTable => {
    return {
        title,
        days: `${days} days`,
        pricePerDay: priceUsdFormat(pricePerDay),
        total: priceUsdFormat(total)
    }
}
</script>
