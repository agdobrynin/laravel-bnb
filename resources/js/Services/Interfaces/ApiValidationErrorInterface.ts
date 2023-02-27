export interface ApiValidationErrorInterface {
    /**
     * First validation message (main).
     */
    message: string

    /**
     * Get validation messages for field in form.
     *
     * @param fieldName Field name in form
     */
    getErrorsByField(fieldName: string): undefined | string[]

    validationErrors: string[]
}
