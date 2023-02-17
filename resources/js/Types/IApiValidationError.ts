export interface IApiValidationError {
    message: string,
    errors: {
        [key: string]: string[]
    }
}
