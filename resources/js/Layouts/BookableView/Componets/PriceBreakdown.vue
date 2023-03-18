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
import { usePriceBreakdownTable } from '@/Composable/usePriceBreakdownTable'
import type { ICalculateBookingInfo } from '@/Types/ICalculateBooking'
import type { IFormattedTable } from '@/Types/IFormattedTable'

const props = defineProps<{calculateBooking: ICalculateBookingInfo}>()

const totalPrice = computed<string>(() => priceUsdFormat(props.calculateBooking.totalPrice || 0))

const table = computed<IFormattedTable[]>(() =>  usePriceBreakdownTable(props.calculateBooking))
</script>
