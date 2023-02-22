<template lang="pug">
div.text-center.fs-1.text-muted
    SvgIcon(
        type="mdi"
        :size="iconSize"
        :path="mdiAlertOctagramOutline"
    )
    p(:class="mtClass")
        slot
</template>

<script lang="ts" setup>
//@ts-ignore
import SvgIcon from '@jamescoyle/vue-icon'
import { mdiAlertOctagramOutline } from '@mdi/js'
import { computed, defineProps, withDefaults } from 'vue'


const props = withDefaults(
    defineProps<{ iconSize?: number }>(),{ iconSize: 120 }
)

const mtClass = computed(() => props.iconSize < 80 ? 'mt-2' : 'mt-5')
</script>
