<template lang="pug">
span(:title="currentRating")
    span(
        v-for="fullStar in fullStars"
        :key="`full-${fullStar}`"
        :title="fullStar"
        :class="{'hand' : !readOnly}"
        @click="update(fullStar)"
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
        :class="{'hand' : !readOnly}"
        @click="update(fullStars + emptyStar)")
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
    defineProps<{ modelValue: number, maxRating?: number, iconSize?: number, readOnly?: boolean }>(),
    {
        maxRating: 5,
        iconSize: 24,
        readOnly: true,
    }
)

const currentRating = computed(() => props.modelValue > props.maxRating ? 0 : props.modelValue)

const halfStar = computed(() => {
    const partOfHalf = +((currentRating.value % 1) * 100).toFixed()

    return partOfHalf > 0 && partOfHalf < 50
})

const fullStars = computed(() => Math.round(currentRating.value))

const emptyStars = computed(() => props.maxRating - Math.ceil(currentRating.value))

const emit = defineEmits<{
    (e: 'update:modelValue', rate: number): void
}>()

const update = (rate: number) => {
    if (!props.readOnly) {
        emit('update:modelValue', rate)
    }
}
</script>

<style scoped lang="css">
.hand {
    cursor: pointer;
}
em {
    font-style: normal;
    padding: 0.8em;
}
</style>
