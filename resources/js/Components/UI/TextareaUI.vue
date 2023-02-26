<template lang="pug">
.form-floating
    textarea#description.form-control.textarea-field(
        :id="id"
        :disabled="disabled"
        :class="[{'is-invalid': validationErrors.length}, inputClass]"
        :placeholder="placeholder"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
     )
    label.form-label(
        v-if="label"
        :class="labelClass"
        :for="id") {{ label }}
    div.invalid-feedback(
        v-for="(error, index) in validationErrors"
        :key="`error_${id}_${index}`") {{ error }}
</template>

<script lang="ts" setup>
import { computed } from 'vue'

import { randomString } from '@/Composable/utils'

const prpos = withDefaults(
    defineProps<{
        modelValue: string,
        errors?: string[],
        label?: string,
        id?: string,
        disabled?: boolean,
        placeholder?: string,
        inputClass?: string,
        labelClass?: string,
    }>(),
    {
        errors: undefined,
        label: undefined,
        id: randomString(),
        disabled: false,
        placeholder: undefined,
        inputClass: undefined,
        labelClass: undefined,
    }
)

const validationErrors = computed(() => prpos.errors || [])

defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()
</script>

<style scoped>
.textarea-field {
    height: 8rem;
}
</style>
