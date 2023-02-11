<template>
    <div>
        Component One 🚀 {{ printable }}
    </div>
</template>

<script setup lang="ts">
import {computed, onMounted, ref, Ref} from 'vue';

const date: Ref<Date> = ref(new Date())

const printable = computed(() => date.value.toTimeString())

onMounted(() => {
    console.log('примонтирован!')
})
</script>
