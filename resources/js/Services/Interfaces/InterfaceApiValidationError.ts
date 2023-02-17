export interface InterfaceApiValidationError {
    /**
     * First validation message (main).
     */
    get message(): string

    /**
     * Get validation messages for field in form.
     *
     * @param fieldName Field name in form
     */
    getErrorsByField(fieldName: string): undefined | string[]
}
