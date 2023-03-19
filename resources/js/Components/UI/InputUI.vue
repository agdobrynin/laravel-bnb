<template lang="pug">
.form-floating
    input.form-control(
        :id="elId"
        :value="modelValue"
        :type="type"
        :placeholder="placeholder"
        :min="min"
        :class="[{'is-invalid': validationErrors.length}, inputClass]"
        :disabled="disabled"
        :readonly="readonly"
        @input="$emit('update:modelValue', $event.target.value)")
    label.form-label(
        v-if="label"
        :class="labelClass"
        :for="elId") {{ label }}
    div.text-muted.fw-light.small.mt-2(v-if="help") {{ help }}
    div.invalid-feedback(
        v-for="(error, index) in validationErrors"
        :key="`error_${id}_${index}`") {{ error }}
</template>

<script lang="ts" setup>
import { computed } from 'vue'

import { randomString } from '@/Composable/utils'

const props = withDefaults(
    defineProps<{
        modelValue?: string|number,
        type?: string,
        errors?: string[],
        label?: string,
        id?: string,
        disabled?: boolean,
        readonly?: boolean,
        placeholder?: string,
        help?: string
        inputClass?: string,
        labelClass?: string,
        min?: string,
    }>(),
    {
        modelValue: '',
        type: 'text',
        errors: undefined,
        label: undefined,
        id: undefined,
        disabled: false,
        readonly: false,
        placeholder: undefined,
        help: undefined,
        inputClass: undefined,
        labelClass: undefined,
        min: undefined,
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
