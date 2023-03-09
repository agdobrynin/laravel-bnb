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
        @input="$emit('update:modelValue', $event.target.value)")
    label.form-label(
        v-if="label"
        :class="labelClass"
        :for="elId") {{ label }}
    div.invalid-feedback(
        v-for="(error, index) in validationErrors"
        :key="`error_${id}_${index}`") {{ error }}
</template>

<script lang="ts" setup>
import { computed, onMounted } from 'vue'

import { randomString } from '@/Composable/utils'

const props = withDefaults(
    defineProps<{
        modelValue: string,
        type?: string,
        errors?: string[],
        label?: string,
        id?: string,
        disabled?: boolean,
        placeholder?: string,
        inputClass?: string,
        labelClass?: string,
        min?: string
    }>(),
    {
        type: 'text',
        errors: undefined,
        label: undefined,
        id: undefined,
        disabled: false,
        placeholder: undefined,
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

onMounted(() => {})
</script>
