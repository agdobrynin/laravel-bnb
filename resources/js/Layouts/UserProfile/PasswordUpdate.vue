<template lang="pug">
form.p-3(@submit.prevent="doUpdate")
    fieldset(:disabled="isLoading")
        transition
            AlertDisplay.alert.alert-danger(v-if="apiError") {{ apiError }}
        transition
            AlertDisplay.alert.alert-success(v-if="isSuccess") Password was changes!
        .row.justify-content-center
            .mb-3.col-12
                InputUI(
                    v-model="form.currentPassword"
                    label="Current password"
                    type="password"
                    :errors="validation('current_password')")
            .mb-3.col-12.col-md-6
                InputUI(
                    v-model="form.password"
                    label="New password"
                    type="password"
                    :errors="validation('password')")
            .mb-3.col-12.col-md-6
                InputUI(
                    v-model="form.passwordConfirmation"
                    label="Retype new password"
                    type="password"
                    :errors="validation('password_confirmation')")
            .mb-3.col-12
                ButtonWithLoading.btn.btn-primary.w-100(
                    :is-loading="isLoading"
                    btn-type="submit") Update
</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import InputUI from '@/Components/UI/InputUI.vue'
import { useApiErrors } from '@/Composable/useApiErrors'
import { HttpProfileService } from '@/Services/HttpProfileService'
import type { IUserPasswordChange } from '@/Types/IUser'

const isLoading = ref<boolean>(false)
const isSuccess = ref<boolean>(false)
const { errors, apiError, validation } = useApiErrors()

const form = reactive<IUserPasswordChange>({
    password: '',
    passwordConfirmation: '',
    currentPassword: '',
})

const clearSuccess = () => isSuccess.value = false
const doUpdate = async () => {
    isLoading.value = true
    isSuccess.value = false
    errors(null)

    try {
        await new HttpProfileService()
            .updateProfilePassword(form)

        form.password = ''
        form.passwordConfirmation = ''
        form.currentPassword = ''
        isSuccess.value = true

        setTimeout(clearSuccess, 2000)
    } catch (e) {
        errors(e)
    }

    isLoading.value = false
}
</script>

