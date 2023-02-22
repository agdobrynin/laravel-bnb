<template lang="pug">
span.hand(:title="currentRating")
    span(
        v-for="fullStar in fullStars"
        :key="`full-${fullStar}`"
        :title="fullStar"
        @click="$emit('update:modelValue', fullStar)"
    )
        SvgIcon(
            type="mdi"
            :size="iconSize"
            :path="mdiStar")
    span(v-if="halfStar")
        SvgIcon(
            type="mdi"
            :size="iconSize"
            :path="mdiStarHalfFull")
    span(
        v-for="emptyStar in emptyStars"
        :key="`empty-${emptyStar}`"
        :title="fullStars + emptyStar"
        @click="$emit('update:modelValue', (fullStars + emptyStar))")
        SvgIcon(
            type="mdi"
            :size="iconSize"
            :path="mdiStarOutline")

    em.badge.bg-secondary.ms-2 {{ currentRating }}
</template>

<script lang="ts" setup>
//@ts-ignore
import SvgIcon from '@jamescoyle/vue-icon'
import { mdiStar, mdiStarHalfFull, mdiStarOutline } from '@mdi/js'
import { computed, defineProps, withDefaults } from 'vue'

const props = withDefaults(
    defineProps<{ modelValue: number, maxRating?: number, iconSize?: number }>(),
    {
        maxRating: 5,
        iconSize: 24,
    }
)

const currentRating = computed(() => props.modelValue > props.maxRating ? 0 : props.modelValue)

const halfStar = computed(() => {
    const partOfHalf = +((currentRating.value % 1) * 100).toFixed()

    return partOfHalf > 0 && partOfHalf < 50
})

const fullStars = computed(() => Math.round(currentRating.value))

const emptyStars = computed(() => props.maxRating - Math.ceil(currentRating.value))

defineEmits<{
    (e: 'update:modelValue', rate: number): void
}>()
</script>

<style scoped lang="css">
.hand > span {
    cursor: pointer;
}
</style>
