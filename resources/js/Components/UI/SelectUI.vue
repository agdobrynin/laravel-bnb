<template lang="pug">
.form-floating
    select.form-select(
        :id="elId"
        :value="modelValue"
        :placeholder="placeholder"
        :class="[{'is-invalid': validationErrors.length}, inputClass]"
        :disabled="disabled"
        :readonly="readonly"
        @change="$emit('update:modelValue', $event.target.value)")
        option(
            v-for="(item, index) in items"
            :key="`option_${elId}_${index}`"
            :value="item.value"
        ) {{ item.text }}
    label.form-label(
        v-if="label"
        :class="labelClass"
        :for="elId") {{ label }}
    div.invalid-feedback(
        v-for="(error, index) in validationErrors"
        :key="`error_${id}_${index}`") {{ error }}
</template>

<script lang="ts" setup>
import { computed } from 'vue'

import { randomString } from '@/Composable/utils'

export interface ISelectUIOption {
    value: string, text: string
}

const props = withDefaults(
    defineProps<{
        modelValue?: string,
        errors?: string[],
        label?: string,
        id?: string,
        disabled?: boolean,
        readonly?: boolean,
        placeholder?: string,
        inputClass?: string,
        labelClass?: string,
        items: ISelectUIOption[],
    }>(),
    {
        modelValue: '',
        errors: undefined,
        label: undefined,
        id: undefined,
        disabled: false,
        readonly: false,
        placeholder: undefined,
        inputClass: undefined,
        labelClass: undefined,
    }
)

const validationErrors = computed<string[]>(() => props.errors || [])
const elId = props.id ? props.id : randomString()

defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()
</script>

<style scoped>
input[readonly] {
    background: #EEE;
    color: #666;
    border: solid 1px #CCC;
}
</style>
