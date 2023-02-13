<template lang="pug">
div.card
    div.card-body
        h5.card-title {{ item.title }}
        p.card-text {{ item.content }}
        button.btn.btn-primary Price {{ priceFormat }}
</template>

<script setup lang="ts">
import { computed } from 'vue';

import type { IBookable } from '@/Components/BookableItem/IBookable';
import { priceUsdFormat } from '@/Сomposable/useMoney';

interface Props extends IBookable{}

const props = defineProps<{item: Props}>()
const priceFormat = computed(() => priceUsdFormat(props.item.price))
</script>
