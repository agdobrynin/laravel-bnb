<template lang="pug">
form.p-3(@submit.prevent="doUpdate")
    transition
        AlertDisplay.alert.alert-danger(v-if="apiError") {{ apiError }}
    .row.justify-content-center
        .mb-3.col-12.col-md-6
            InputUI(
                v-model.trim="form.firstName"
                :input-class="inputSuccessClass"
                label="Your name"
                :errors="validation('first_name')"
                @input="clearSuccess")
        .mb-3.col-12.col-md-6
            InputUI(
                v-model.trim="form.lastName"
                :input-class="inputSuccessClass"
                label="Your last name"
                :errors="validation('last_name')"
                @input="clearSuccess")
        .mb-3.col-12
            InputUI(
                v-model.trim="form.email"
                :input-class="inputSuccessClass"
                type="email"
                label="Email"
                :errors="validation('email')"
                help="If email was changed your account will be is unverified"
                @input="clearSuccess")
        .mb-3.col-12.col-md-6
            ButtonWithLoading.btn.btn-outline-secondary.w-100(
                btn-type="button"
                @click.prevent="doRestore"
            ) Reset
        .mb-3.col-12.col-md-6
            ButtonWithLoading.btn.btn-primary.w-100(
                :is-loading="isLoading"
                btn-type="submit"
            ) Update
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'

import AlertDisplay from '@/Components/UI/AlertDisplay.vue'
import ButtonWithLoading from '@/Components/UI/ButtonWithLoading.vue'
import InputUI from '@/Components/UI/InputUI.vue'
import { useApiErrors } from '@/Composable/useApiErrors'
import { HttpProfileService } from '@/Services/HttpProfileService'
import { useAuthStore } from '@/stores/auth'
import type { IUserProfile } from '@/Types/IUser'

const authStore = useAuthStore()
const { errors, apiError, validation } = useApiErrors()

const success = ref<boolean>(true)
const isLoading = ref<boolean>(false)
const form = ref<IUserProfile>({ ...authStore.userProfile })

const inputSuccessClass = computed(() => success.value ? 'is-valid' : '')

const clearSuccess = () => success.value = false

const doRestore = () => {
    form.value = { ...authStore.userProfile }
    success.value = true
    errors(null)
}

const doUpdate = async (): Promise<void> => {
    errors(null)
    isLoading.value = true

    try {
        await new HttpProfileService().updateProfileInformation(form.value)
        await authStore.fetchUser()
        success.value = true
    } catch (e) {
        errors(e)
    }

    isLoading.value = false
}
</script>
