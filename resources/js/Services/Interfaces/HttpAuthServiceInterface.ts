import { IResetPassword } from '@/Types/IResetPassword'
import { IUser, IUserRegister } from '@/Types/IUser'

export interface HttpAuthServiceInterface {
    /**
     * Fetch user data
     *
     * @throws ApiErrorInterface
     */
    fetchUser(): Promise<IUser | null | never>

    /**
     * Login action
     * @throws ApiErrorInterface|ApiValidationErrorInterface
     */
    login(email: string, password: string): Promise<void | never>

    /**
     * Logout action
     * @throws ApiErrorInterface
     */
    logout(): Promise<void | never>

    /**
     * Register new user
     * @throws ApiErrorInterface|ApiValidationErrorInterface
     */
    register(user: IUserRegister): Promise<unknown | never>

    /**
     * Register new user
     * @throws ApiErrorInterface
     */
    resendConfirmLink(): Promise<void | never>

    /**
     * Send link for restore forgot password
     * @throws ApiErrorInterface|ApiValidationErrorInterface
     */
    forgotPassword(email: string): Promise<string | never>

    /**
     * Reset forgot password
     * @throws ApiErrorInterface|ApiValidationErrorInterface
     */
    resetPassword(resetPassword: IResetPassword): Promise<string | never>
}
