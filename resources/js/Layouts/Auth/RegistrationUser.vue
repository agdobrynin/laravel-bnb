<template lang="pug">
div
    transition
        AlertDisplay.alert.alert-danger(v-if="apiError") {{ apiError }}
    form(
        v-if="!success"
        @submit.prevent="doRegistration"
    )
        h4.mb-4.text-center Register new user
        .row.justify-content-center
            .mb-3.col-12.col-md-6
                InputUI(
                    v-model="form.firstName"
                    label="Your name"
                    :errors="validation('first_name')")
            .mb-3.col-12.col-md-6
                InputUI(
                    v-model="form.lastName"
                    label="Your last name"
                    :errors="validation('last_name')")
            .mb-3.col-12
                InputUI(
                    v-model="form.email"
                    label="Email"
                    :errors="validation('email')")
        .row.justify-content-center
            .mb-3.col-12.col-md-6
                InputUI(
                    v-model="form.password"
                    label="Password"
                    type="password"
                    :errors="validation('password')")
            .mb-3.col-12.col-md-6
                InputUI(
                    v-model="form.passwordConfirmation"
                    label="Retype password"
                    type="password"
                    :errors="validation('password_confirmed')")
        .row.justify-content-center
            .mb-3.col-12
                ButtonWithLoading.btn.btn-primary.w-100(
                    :is-loading="isLoading"
                    btn-type="submit"
                ) Register
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
import type { IUserRegister } from '@/Types/IUser'

const isLoading = ref<boolean>(false)
const success = ref<boolean>(false)
const router = useRouter()
const authStore = useAuthStore()

const form: IUserRegister = reactive({
    firstName: '',
    lastName: '',
    email: '',
    password: '',
    passwordConfirmation: '',
})

const { apiError, errors, validation } = useApiErrors()

const doRegistration = async () => {
    errors(null)
    isLoading.value = true
    success.value = false

    try {
        const srv = new HttpAuthService()
        // success return void with http status 201 (created)
        await srv.register(form)
        await authStore.fetchUser()

        success.value = true
    } catch (reason) {
        errors(reason as Error | ApiErrorInterface | ApiValidationErrorInterface)
    }

    isLoading.value = false
}

onBeforeMount(() => {
    if (authStore.user) {
        router.push({ name: 'home' })
    }
})
</script>
