<template lang="pug">
span(:title="currentRating")
    span(
        v-for="fullStar in fullStars"
        :key="`full-${fullStar}`"
    )
        SvgIcon(
            type="mdi"
            :path="mdiStar")
    span(v-if="halfStar")
        SvgIcon(
            type="mdi"
            :path="mdiStarHalfFull")
    span(
        v-for="emptyStar in emptyStars"
        :key="`full-${emptyStar}`"
    )
        SvgIcon(
            type="mdi"
            :path="mdiStarOutline")

    span.badge.bg-secondary.ms-2 {{ rating }}
</template>

<script lang="ts" setup>
//@ts-ignore
import SvgIcon from '@jamescoyle/vue-icon'
import { mdiStar, mdiStarHalfFull, mdiStarOutline } from '@mdi/js'
import { computed, defineProps, withDefaults } from 'vue'

const props = withDefaults(
    defineProps<{ rating: number, maxRating?: number }>(),
    {
        maxRating: 5
    }
)

const currentRating = computed(() => props.rating > props.maxRating ? 0 : props.rating)

const halfStar = computed( ()=> {
    const partOfHalf = +((currentRating.value % 1) * 100).toFixed()

    return partOfHalf > 0 && partOfHalf < 50
})

const fullStars = computed(() => Math.round(currentRating.value))

const emptyStars = computed(() => props.maxRating - Math.ceil(currentRating.value))
</script>
