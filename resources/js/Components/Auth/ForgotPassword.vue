<template lang="pug">
form(@submit.prevent="doRestore")
    transition
        AlertDisplay(v-if="apiError") {{ apiError }}
    transition
        div(v-if="successMessage === null")
            .row.justify-content-center
                .mb-3.col-12.col-md-6
                    InputUI(
                        v-model.trim="email"
                        label="Email"
                        :errors="validation('email')")
            .row.justify-content-center
                .mb-3.col-12.col-md-6.text-center
                    ButtonWithLoading.btn.btn-primary.w-100.mb-4(
                        :is-loading="isLoading"
                        btn-type="submit") Restore
        div(v-else)
            AlertDisplay.alert.alert-success(:svg-icon="mdiHandOkay") {{ successMessage }}
</template>

<script lang="ts" setup>
import { mdiHandOkay } from '@mdi/js'
import { ref } from 'vue'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import InputUI from '@/Components/UI/InputUI.vue'
import { useApiErrors } from '@/Composable/useApiErrors'
import { HttpAuthService } from '@/Services/HttpAuthService'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { ApiValidationErrorInterface } from '@/Services/Interfaces/ApiValidationErrorInterface'

const isLoading = ref<boolean>(false)
const successMessage = ref<string | null>(null)
const email = ref<string>('')

const { apiError, errors: setApiErrors, validation } = useApiErrors()

const doRestore = async (): Promise<void> => {
    setApiErrors(null)
    isLoading.value = true
    successMessage.value = null

    try {
        successMessage.value = await new HttpAuthService().forgotPassword(email.value)
    } catch (reason) {
        setApiErrors(reason  as Error | ApiErrorInterface | ApiValidationErrorInterface)
    }

    isLoading.value = false
}
</script>
