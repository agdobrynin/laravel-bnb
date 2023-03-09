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
}
