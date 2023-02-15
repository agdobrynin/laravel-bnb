import { AxiosError } from 'axios'

import type { InterfaceApiError } from '@/Services/InterfaceApiError'
import type { IApiError } from '@/Types/IApiError'

export class ApiError implements InterfaceApiError {
    constructor(private readonly axiosError: AxiosError) {
    }

    get backendMessage(): string {
        // @ts-ignore
        const { message } = this.axiosError.response?.data || this.axiosError

        return message
    }

    get httpStatus(): number {
        return this.axiosError.response?.status || 400
    }

    get requestErrorMessage(): string {
        return this.axiosError.message
    }

    get backendResponse(): IApiError|undefined {
        return this.axiosError.response?.data as IApiError || undefined
    }
}
