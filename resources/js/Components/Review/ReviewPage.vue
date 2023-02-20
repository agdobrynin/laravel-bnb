<template lang="pug">
div
    .mb-3
        label.form-label(for="rating") Set rating (1 is worst &mdash; 5 is best)
        RatingItem.hand.form-control.border-0.ps-0#rating(
            v-model="review.rating"
            :max-rating="5"
            :icon-size="43")
    .mb-3.form-floating
        textarea#description.form-control.description-field(
            v-model="review.description"
            name="description" )
        label.form-label(for="description") Describe your experience with
    ButtonWithLoading.btn.btn-primary.w-100(
        title="Send"
        :is-loading="isLoading")
</template>

<script setup lang="ts">
import { ref } from 'vue'

import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import RatingItem from '@/Components/UI/RatingItem.vue'

const review = ref({
    rating: 5,
    description: null,
})
const isLoading = ref(false)
</script>

<style scoped lang="css">
.hand {
    cursor: pointer;
}
.description-field {
    height: 8rem;
}
</style>
