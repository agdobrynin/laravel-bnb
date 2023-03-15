import { ComputedRef } from 'vue'
import { computed, Ref, ref } from 'vue'

import { ApiError } from '@/Services/ApiError'
import { ApiValidationError } from '@/Services/ApiValidationError'
import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { ApiValidationErrorInterface } from '@/Services/Interfaces/ApiValidationErrorInterface'

/**
 *
 * @throws Error
 */
export type inputErrorsFunction = (error: Error | ApiErrorInterface | ApiValidationErrorInterface | null | unknown) => void | never;

export type validationFunction = (validationKey: string) => string[];
export interface IApiErrorsResult {
    apiError: Ref<null | string>,
    validationErrors: Ref<null | ApiValidationErrorInterface>

    errors: inputErrorsFunction,
    validation: ComputedRef<validationFunction>,
}
export const useApiErrors = (): IApiErrorsResult => {

    const validationErrors = ref<null | ApiValidationErrorInterface>(null)
    const apiError = ref<null | string>(null)

    /**
     *
     * @throws Error
     */
    const errors = (error: Error | ApiErrorInterface | ApiValidationErrorInterface | null | unknown): void | never => {
        if (error === null) {
            validationErrors.value = null
            apiError.value = null
        } else if (error instanceof ApiValidationError) {
            validationErrors.value = error
        } else if (error instanceof ApiError) {
            apiError.value = error.apiError?.message || error.requestError
        } else if (error instanceof Error) {
            apiError.value = (error as Error).message
        } else {
            throw Error('Un support input type')
        }
    }

    const validation = computed(() => (validationKey: string): string[] => validationErrors.value?.getErrorsByField(validationKey) || [])


    return {
        validation,
        errors,
        validationErrors,
        apiError,
    }
}
