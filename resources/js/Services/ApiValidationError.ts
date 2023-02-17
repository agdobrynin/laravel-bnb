import { InterfaceApiValidationError } from '@/Services/Interfaces/InterfaceApiValidationError'
import { IApiValidationError } from '@/Types/IApiValidationError'

export class ApiValidationError implements  InterfaceApiValidationError {
    constructor(private readonly data: IApiValidationError) {
    }

    get message(): string {
        return this.data.message
    }

    getErrorsByField(fieldName: string): undefined | string[] {
        return this.data.errors[fieldName]
    }
}
