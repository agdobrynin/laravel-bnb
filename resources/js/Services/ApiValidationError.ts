import { AxiosError } from 'axios'

import { ApiValidationErrorInterface } from '@/Services/Interfaces/ApiValidationErrorInterface'
import { IApiValidationError } from '@/Types/IApiValidationError'

export class ApiValidationError implements  ApiValidationErrorInterface {
    constructor(private readonly axiosError: AxiosError) {
    }

    get message(): string {
        const { message }  = this.axiosError.response?.data  as IApiValidationError || 'Unknown error'

        return message
    }

    getErrorsByField(fieldName: string): undefined | string[] {
        const { errors } = this.axiosError.response?.data as IApiValidationError || []

        return errors[fieldName]
    }
}
