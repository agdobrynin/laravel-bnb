<template lang="pug">
Transition
    form(
        v-if="successMessage === null"
        @submit.prevent="doResetPassword"
    )
        h4.text-center.mb-4 Reset my password
        Transition
            AlertDisplay(v-if="apiError") {{ apiError }}
        .row.justify-content-center
            .mb-3.col-12
                InputUI(
                    :model-value="form.email"
                    :readonly="true"
                    label="email"
                    type="text"
                    :errors="validation('email')")
            .mb-3.col-12.col-md-6
                InputUI(
                    v-model="form.password"
                    :readonly="isLoading"
                    label="Password"
                    type="password"
                    :errors="validation('password')")
            .mb-3.col-12.col-md-6
                InputUI(
                    v-model="form.passwordConfirmation"
                    :readonly="isLoading"
                    label="Retype password"
                    type="password"
                    :errors="validation('password_confirmed')")
        .row.justify-content-center
            .mb-3.col-12
                ButtonWithLoading.btn.btn-primary.w-100(
                    :is-loading="isLoading"
                    btn-type="submit"
                ) Reset my password
    div(v-else)
        AlertDisplay.alert.alert-success(
            :svg-icon="mdiHandOkay"
        )
            | {{ successMessage }}
            p #[router-link(:to="{name: 'login'}") Login with new password]
</template>

<script lang="ts" setup>
import { mdiHandOkay } from '@mdi/js'
import { onMounted, reactive, ref } from 'vue'
import { useRoute } from 'vue-router'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import InputUI from '@/Components/UI/InputUI.vue'
import { useApiErrors } from '@/Composable/useApiErrors'
import { HttpAuthService } from '@/Services/HttpAuthService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { ApiValidationErrorInterface } from '@/Services/Interfaces/ApiValidationErrorInterface'
import type { IResetPassword } from '@/Types/IResetPassword'

const route = useRoute()

const form = reactive<IResetPassword>({
    token: '',
    email: '',
    password: '',
    passwordConfirmation: ''
})

const isLoading = ref<boolean>(false)

const { apiError, errors, validation } = useApiErrors()
const successMessage = ref<string|null>(null)


const doResetPassword = async () => {
    isLoading.value = true
    errors(null)

    try {
        successMessage.value = await new HttpAuthService().resetPassword(form)
    } catch (reason) {
        errors(reason as Error | ApiErrorInterface | ApiValidationErrorInterface)
    }

    isLoading.value = false
}

onMounted(() => {
    const { token = '', email = '' } = route.query

    form.token = String(token)
    form.email = String(email)
})
</script>
