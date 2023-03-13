<template lang="pug">
form(@submit.prevent="doLogin")
    transition
        .row.justify-content-center(v-if="apiError")
            .mb-3.col-12.col-md-6
                AlertDisplay.alert.alert-danger {{ apiError }}
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
        .mb-3.col-12.col-md-6.text-center
            ButtonWithLoading.btn.btn-primary.w-100.mb-4(
                :is-loading="isLoading"
                btn-type="submit") Sign in
            router-link.text-muted(:to="{name: 'forgot-password'}") Forgot password?
</template>

<script lang="ts" setup>
import { onBeforeMount, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import InputUI from '@/Components/UI/InputUI.vue'
import { useApiErrors } from '@/Composable/useApiErrors'
import { HttpAuthService } from '@/Services/HttpAuthService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { ApiValidationErrorInterface } from '@/Services/Interfaces/ApiValidationErrorInterface'
import { useAuthStore } from '@/stores/auth'

const isLoading = ref<boolean>(false)
const authStore = useAuthStore()
const router = useRouter()

const { apiError, errors, validation } = useApiErrors()

const form = reactive({
    email: '',
    password: '',
})

const doLogin = async () => {
    errors(null)
    isLoading.value = true

    try {
        const srv = new HttpAuthService()

        await srv.login(form.email, form.password)
        await authStore.fetchUser()

        await router.push({ name: 'home' })
    } catch (reason) {
        errors(reason as Error | ApiErrorInterface | ApiValidationErrorInterface)
        authStore.user = null
    }

    isLoading.value = false
}

onBeforeMount(async () => {
    if (authStore.user) {
        await router.push({ name: 'home' })
    } else {
        if (Object.keys(router.currentRoute.value.query).includes('verification.verify')) {
            errors(new Error('For verification email you must be authorized'))
        }
    }
})
</script>
