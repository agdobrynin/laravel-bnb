<template lang="pug">
div
    div.row.justify-content-center.row-cols-1.row-cols-md-2.row-cols-lg-3.row-cols-xl-4.g-4(v-if="loading === false")
        div.col(v-for="bookable in bookables" :key="bookable.id")
            BookableItem(:item="bookable")
        div(v-if="bookables.length === 0") Not found books.
    div.row.justify-content-center.row-cols-1.row-cols-md-2.g-4(v-else)
        div.col(v-for="index in [0, 1, 2]" :key="index")
            PlaceholderCard
</template>

<script setup lang="ts">
import { onMounted, reactive, ref } from 'vue';

import BookableItem from '@/Components/BookableItem/BookableItem.vue';
import type { IBookable } from '@/Components/BookableItem/IBookable';
import PlaceholderCard from '@/Components/PlaceholderCard/PlaceholderCard.vue';


const bookables: IBookable[] = reactive([])
const loading = ref(true)

onMounted(() => {
    setTimeout(() => {
        bookables.push({ id: 1, title: 'First item', content: 'Content for 1 item', price: 1000 })
        bookables.push({ id: 2, title: 'Second item', content: 'Content for 2 item', price: 300 })
        bookables.push({ id: 3, title: 'Treed item', content: 'Content for 3 item', price: 4500 })
        bookables.push({ id: 4, title: 'Fourth item', content: 'Content for 4 item', price: 100 })
        bookables.push({ id: 5, title: 'Fives item', content: 'Content for 5 item', price: 2300 })
        bookables.push({ id: 6, title: 'Six item', content: 'Content for 6 item', price: 3100 })
        loading.value = false
    }, 2000)
});
</script>
