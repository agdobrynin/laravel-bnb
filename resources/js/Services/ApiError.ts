import { AxiosError } from 'axios'

import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { IApiError } from '@/Types/IApiError'

export class ApiError implements ApiErrorInterface {
    constructor(private readonly axiosError: AxiosError) {
    }

    get httpStatus(): number {
        return this.axiosError.response?.status || 400
    }

    get requestError(): string {
        return this.axiosError.message
    }

    get apiError(): IApiError | undefined {
        return this.axiosError.response?.data as IApiError || undefined
    }
}
