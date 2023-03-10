<template lang="pug">
div
    Transition
        AlertDisplay.alert.alert-danger(v-if="apiError") {{ apiError }}
    Transition
        PlaceholderCard(v-if="inProgress")
            h4.text-center Please wait...
    Transition
        AlertDisplay.alert.alert-success(v-if="success") {{ success }}
</template>

<script lang="ts" setup>
import { storeToRefs } from 'pinia'
import { ref } from 'vue'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import { HttpAuthService } from '@/Services/HttpAuthService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import { useAuthStore } from '@/stores/auth'

const authStore = storeToRefs(useAuthStore())
const apiError = ref<ApiErrorInterface | null>(null)
const inProgress = ref<boolean>(true)
const success = ref<string | null>(null)

const sendConfirm = async () => {
    try {
        await (new HttpAuthService()).resendConfirmLink()
        success.value = 'Verification link was send to your email'
    } catch (e) {
        apiError.value = e as ApiErrorInterface
    } finally {
        inProgress.value = false
    }
}

if (authStore.user.value?.isVerified === false) {
    sendConfirm()
} else {
    success.value = 'Your email already verified'
    inProgress.value = false
}
</script>
