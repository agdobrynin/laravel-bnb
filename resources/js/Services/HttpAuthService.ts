import { AxiosError } from 'axios'
import snakecaseKeys from 'snakecase-keys'

import { ApiError } from '@/Services/ApiError'
import { HttpServiceAbstract } from '@/Services/HttpServiceAbstract'
import { HttpAuthServiceInterface } from '@/Services/Interfaces/HttpAuthServiceInterface'
import { IResetPassword } from '@/Types/IResetPassword'
import { IUser, IUserRegister } from '@/Types/IUser'
import { IVerifyEmail } from '@/Types/IVerifyEmail'

export class HttpAuthService extends HttpServiceAbstract implements HttpAuthServiceInterface {
    constructor(endpoint?: string) {
        super(endpoint)
    }

    async fetchUser(): Promise<IUser | null> {
        try {
            return <IUser>(await this.client.get('/api/user')).data
        } catch (e) {
            return null
        }
    }

    async login(email: string, password: string): Promise<void | never> {
        try {
            await this.client.post('/login', { email, password })
        } catch (e) {
            throw this.errorClassForThrow(e)
        }
    }

    async logout(): Promise<void | never> {
        try {
            await this.client.post('/logout')
        } catch (e) {
            throw this.errorClassForThrow(e)
        }
    }

    async register(user: IUserRegister): Promise<void | never> {
        try {
            await this.client.post('/register', snakecaseKeys(user))
        } catch (e) {
            throw this.errorClassForThrow(e)
        }
    }

    async resendConfirmLink(): Promise<void | never> {
        try {
            await this.client.post('/email/verification-notification')
        } catch (e) {
            throw new ApiError(e as AxiosError)
        }
    }

    async forgotPassword(email: string): Promise<string | never> {
        try {
            return <string>(
                await this.client.post('/forgot-password', { email })
            ).data.message || 'We send email with reset link.'
        } catch (e) {
            throw this.errorClassForThrow(e)
        }
    }

    async resetPassword(resetPassword: IResetPassword): Promise<string | never> {
        try {
            return <string>(
                await this.client.post('/reset-password', snakecaseKeys(resetPassword))
            ).data.message || 'Your password has been reset.'
        } catch (e) {
            throw this.errorClassForThrow(e)
        }
    }

    async verifyEmail(data: IVerifyEmail): Promise<void | never> {
        try {
            const { id, hash, expires, signature } = data
            const params = { expires, signature }

            await this.client.get(`/email/verify/${id}/${hash}`, { params })
        } catch (e) {
            throw this.errorClassForThrow(e)
        }
    }
}
