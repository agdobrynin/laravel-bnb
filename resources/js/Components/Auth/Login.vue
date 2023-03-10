<template lang="pug">
form(@submit.prevent="doLogin")
    transition
        AlertDisplay(v-if="apiError") {{ apiError }}
    .row.justify-content-center
        .mb-3.col-12.col-md-6
            InputUI(
                v-model.trim="form.email"
                label="Email"
                :errors="validation('email')")
    .row.justify-content-center
        .mb-3.col-12.col-md-6
            InputUI(
                v-model.trim="form.password"
                label="Password"
                type="password"
                :errors="validation('password')")
    .row.justify-content-center
        .mb-3.col-12.col-md-6
            ButtonWithLoading.btn.btn-primary.w-100(
                :is-loading="isLoading"
                btn-type="submit") Sign in
</template>

<script lang="ts" setup>
import { computed, onBeforeMount, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import InputUI from '@/Components/UI/InputUI.vue'
import { ApiError } from '@/Services/ApiError'
import { ApiValidationError } from '@/Services/ApiValidationError'
import { HttpAuthService } from '@/Services/HttpAuthService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { ApiValidationErrorInterface } from '@/Services/Interfaces/ApiValidationErrorInterface'
import { useAuthStore } from '@/stores/auth'

const isLoading = ref<boolean>(false)
const authStore = useAuthStore()
const router = useRouter()
const apiError = ref<string|null>(null)
const validationError = ref<ApiValidationErrorInterface|null>(null)

const validation = computed(() => (field: string): string[] => {
    return validationError.value?.getErrorsByField(field) || []
})

const form = reactive({
    email: '',
    password: '',
})

const doLogin = async () => {
    validationError.value = null
    apiError.value = null
    isLoading.value = true

    try {
        const srv = new HttpAuthService()

        await srv.login(form.email, form.password)
        await authStore.fetchUser()

        await router.push({ name: 'home' })
    } catch (reason) {
        const error = reason as Error | ApiErrorInterface | ApiValidationErrorInterface

        if (error instanceof ApiValidationError) {
            validationError.value = error
        } else if (error instanceof ApiError) {
            apiError.value = error.apiError?.message || error.requestError
        } else {
            apiError.value = (error as Error).message
        }

        authStore.user = null
    }

    isLoading.value = false
}

onBeforeMount(() => {
    if (authStore.user) {
        router.push({ name: 'home' })
    }
})
</script>
