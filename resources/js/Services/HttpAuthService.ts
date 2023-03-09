import snakecaseKeys from 'snakecase-keys'

import { HttpServiceAbstract } from '@/Services/HttpServiceAbstract'
import { HttpAuthServiceInterface } from '@/Services/Interfaces/HttpAuthServiceInterface'
import { IUser, IUserRegister } from '@/Types/IUser'

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
}
