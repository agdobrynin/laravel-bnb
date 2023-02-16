<template lang="pug">
div
    h6.text-uppercase.text-secondary Check availability
    div.row.gap-2
        div.col-md.form-floating.mb-3
            input#dateFrom.form-control.form-control-sm(
                type="date"
                placeholder="Date from"
                v-model="data.start"
                :min="data.startMin"
            )
            label.text-secondary.col-form-label-sm(for="dateFrom") Date from
        div.col-md.form-floating.mb-3
            input#dateTo.form-control-sm.form-control(
                type="date"
                placeholder="Date to"
                v-model="data.end"
                :min="data.endMin"
            )
            label.text-secondary.col-form-label-sm(for="dateTo") Date to
    button.btn.btn-secondary.w-100 Check
</template>

<script setup lang="ts">
import { reactive } from 'vue'

import { DateRange } from '@/Models/DateRange'

const dateRange: DateRange = new DateRange(new Date(), new Date())

const data = reactive(dateRange)
</script>

<style scoped lang="scss">
.form-floating{
    label {
        left: 1.2rem
    }
}
</style>
