<template lang="pug">
div.fs-1.text-muted.d-flex.align-items-center
    div
        SvgIcon(
            type="mdi"
            :size="iconSize"
            :path="icon"
        )
    div.ms-4
        slot
            h1 We've encountered a problem
            h2 Unfortunately we cannot proceed
</template>

<script lang="ts" setup>
//@ts-ignore
import SvgIcon from '@jamescoyle/vue-icon'
import { mdiAlertOctagramOutline } from '@mdi/js'
import { computed } from 'vue'

const props = withDefaults(
    defineProps<{ iconSize?: number, svgIcon?: string }>(),{ iconSize: 120, svgIcon: undefined }
)

const icon = computed(() => props.svgIcon || mdiAlertOctagramOutline)
</script>
