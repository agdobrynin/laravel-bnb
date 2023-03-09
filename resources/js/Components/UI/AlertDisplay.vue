<template lang="pug">
div.fs-1.text-muted.d-flex.align-items-center
    div
        SvgIcon(
            type="mdi"
            :size="iconSize"
            :path="mdiAlertOctagramOutline"
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

withDefaults(
    defineProps<{ iconSize?: number }>(),{ iconSize: 120 }
)
</script>
