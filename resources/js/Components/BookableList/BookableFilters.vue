<template lang="pug">
form.container(@submit.prevent="doApply")
    div.row.align-items-end
        .col-md-2.col-lg-4
            div.mb-2.text-muted Categories
            SelectUI(
                v-model="newFilters.bookableCategoryId",
                :items="categories"
                label="Category"
                input-class="form-select-sm")
        .col-md-5.col-lg-4
            .row.mx-auto
                .col-12.mb-2.text-muted Regular price
                .col-6.p-0
                    InputUI(
                        v-model.number="newFilters.priceMin"
                        type="number"
                        input-class="rounded-0 rounded-start form-control-sm"
                        label="Min")
                .col-6.p-0
                    InputUI(
                        v-model.number="newFilters.priceMax"
                        type="number"
                        label="Max"
                        input-class="rounded-0 rounded-end border-start-0 form-control-sm")
        .col-md-5.col-lg-4
            .row.mx-auto
                .col-12.mb-2.text-muted Weekend price
                .col-6.p-0
                    InputUI(
                        v-model.number="newFilters.priceWeekendMin"
                        type="number"
                        input-class="rounded-0 rounded-start form-control-sm"
                        label="Min")
                .col-6.p-0
                    InputUI(
                        v-model.number="newFilters.priceWeekendMax"
                        type="number"
                        input-class="rounded-0 rounded-end border-start-0 form-control-sm"
                        label="Max")
        .col-12.mx-auto.mt-4.text-end
            button.btn.btn-outline-secondary(
                type="reset"
                @click.prevent="doReset"
            ) Clear
            |&nbsp;
            button.btn.btn-outline-success(type="submit") Apply
</template>

<script lang="ts" setup>
import { onBeforeMount, reactive, ref } from 'vue'

import InputUI from '@/Components/UI/InputUI.vue'
import type { ISelectUIOption } from '@/Components/UI/SelectUI.vue'
import SelectUI from '@/Components/UI/SelectUI.vue'
import type { IBookableCategoryItem } from '@/Types/IBookableCategoryItem'
import type { IBookableListFilters } from '@/Types/IBookableListFilters'

const props = defineProps<{
    categories: IBookableCategoryItem[]
    filters: IBookableListFilters
}>()

const newFilters = reactive<IBookableListFilters>( { ... props.filters })

const emit = defineEmits<{
    (e: 'changeFilters', filters: IBookableListFilters): void
}>()

const categories = ref<ISelectUIOption[]>([])
const doApply = () => {
    emit('changeFilters', newFilters)
}

const doReset = () => {
    newFilters.bookableCategoryId = undefined
    newFilters.priceMin = undefined
    newFilters.priceMax = undefined
    newFilters.priceWeekendMin = undefined
    newFilters.priceWeekendMax = undefined

    doApply()
}

onBeforeMount(async () => {
    categories.value = props.categories.map((item) => {
        return { text: item.name, value: item.id }
    }) || []
    categories.value.unshift({ text: 'All categories', value: '' })
})
</script>
