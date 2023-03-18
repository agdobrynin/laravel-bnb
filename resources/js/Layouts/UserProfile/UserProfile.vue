<template lang="pug">
div(v-if="!isVerified")
    p Please check your email box and confirm your account by verification link.
    p Resend confirmation link to your email #[router-link(:to="{name: 'resend-confirm-link'}") again].
div(v-else)
    ul.nav.nav-tabs.flex-column.flex-md-row
        li.nav-item(
            v-for="(title, tabId) in mapTabs"
            :key="`tab_${tabId}`"
        )
            a.nav-link(
                href=""
                :class="[{'active': currentTabKey === tabId}]"
                @click.prevent="doLoadTab(tabId)"
            ) {{ title }}
    div.border.border-top-0
        AlertDisplay(v-if="errorTabLoader") {{ errorTabLoader }}
        div(v-if="currentComponent !== null")
            keep-alive
                component(
                :is="currentComponent"
                :key="currentTabKey"
                v-bind="currentComponent")
        div(v-else) Choose tab.
</template>

<script lang="ts" setup>
import { storeToRefs } from 'pinia'
import type { ComponentPublicInstance } from 'vue'
import { computed, onBeforeMount, ref, shallowRef } from 'vue'
import { useRouter } from 'vue-router'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import { errorTabLoader,mapComponents,mapTabs, tabKey } from '@/Layouts/UserProfile/TabComponents'
import { useAuthStore } from '@/stores/auth'

const authStore = storeToRefs(useAuthStore())
const router = useRouter()

const currentComponent = shallowRef<ComponentPublicInstance | null>(null)
const currentTabKey = ref<tabKey | null>(null)

const isVerified = computed<boolean>(() => Boolean(authStore.user.value?.isVerified))

const doLoadTab = (componentKey: tabKey) => {
    errorTabLoader.value = null
    currentComponent.value = mapComponents[componentKey]
    currentTabKey.value = componentKey
}

onBeforeMount(async () => {
    if (authStore.user.value === null) {
        await router.push({ name: 'login' })
    } else {
        doLoadTab(tabKey.profileUpdate)
    }
})
</script>
