<template lang="pug">
nav.w-100
    ul.pagination.justify-content-center
        li.page-item
            a.page-link(
                href="#"
                :class="[{'disabled' : data.currentPage === 1 }]"
                @click.prevent="changePage(data.currentPage - 1)"
            )
                span(aria-hidden="true") &laquo;
        li.page-item(
            v-for="page in pages"
            :key="`pg-${page}`"
        )
            a.page-link(
                :class="[{'active disabled' : data.currentPage === page}]"
                href="#"
                @click.prevent="changePage(page)") {{ page }}
        li.page-item
            a.page-link(
                href="#"
                :class="[{'disabled' : data.currentPage === data.lastPage }]"
                @click.prevent="changePage(data.currentPage + 1)"
            )
                span(aria-hidden="true") &raquo;
</template>

<script setup lang="ts">
import { computed } from 'vue'

import type { IPaginationData } from '@/Types/IPagination'

interface IProps extends IPaginationData {}

const props = defineProps<{data: IProps}>()

const pages = computed<number>(() => Math.ceil(props.data.total / props.data.perPage))

const emit = defineEmits<{
    (e: 'changePage', value: number): void
}>()

const changePage = (page: number): void => {
    emit('changePage', page)
}
</script>
