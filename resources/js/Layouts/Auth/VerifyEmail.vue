<template lang="pug">
PlaceholderCard.text-center.fs-4(v-if="isLoading" )
    | Verifying email. Please wait...
Transition
    AlertDisplay.alert.alert-danger(v-if="apiError") {{ apiError }}
</template>

<script lang="ts" setup>
import { onBeforeMount, ref } from 'vue'
import { useRouter } from 'vue-router'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import PlaceholderCard from '@/Components/UI/PlaceholderCard.vue'
import { useApiErrors } from '@/Composable/useApiErrors'
import { HttpAuthService } from '@/Services/HttpAuthService'
import { useAuthStore } from '@/stores/auth'
import type { IVerifyEmail } from '@/Types/IVerifyEmail'

const authStore = useAuthStore()
const router = useRouter()
const isLoading = ref<boolean>(true)
const { errors, apiError } = useApiErrors()

onBeforeMount(async () => {
    const { id, hash, expires, signature } = router.currentRoute.value.params as { [key: string]: string }

    const params: IVerifyEmail = { id, hash, expires, signature }
    const srv = new HttpAuthService()

    try {
        await srv.verifyEmail(params)
        // update user store
        await authStore.fetchUser()
        await router.push({ name: 'home' })
    } catch (reason) {
        errors(reason)
    }

    isLoading.value = false
})
</script>
