import axios, { AxiosError, AxiosInstance } from 'axios'

import { ApiError } from '@/Services/ApiError'
import { ApiValidationError } from '@/Services/ApiValidationError'

export abstract class HttpServiceAbstract {
    protected readonly endpoint: string
    protected client: AxiosInstance

    protected constructor(endpoint?: string) {
        this.endpoint = endpoint === undefined
            ? `${import.meta.env.VITE_API_ENDPOINT}`
            : this.endpoint = endpoint

        // use axios interceptors with http code 419 and response json {message: 'CSRF token mismatch.'}

        const instance = axios.create({
            withCredentials: true,
        })

        instance.interceptors.response.use(
            response => response,
            async (error: AxiosError) => {
                const { status } = error.response || {}
                const originalRequest = error.config
                // CSRF protection on backend
                if (status === 419) {
                    await instance.get(`${this.endpoint}/sanctum/csrf-cookie`)

                    return instance(originalRequest || {})
                } else {
                    return Promise.reject(error)
                }
            }
        )

        this.client = instance
    }

    protected errorClassForThrow(reason: unknown): ApiValidationError | ApiError {
        const error = <AxiosError>reason

        return (error.response?.status === 422) ? new ApiValidationError(error) : new ApiError(error)
    }
}
