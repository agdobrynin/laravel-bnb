import snakecaseKeys from 'snakecase-keys'

import { HttpServiceAbstract } from '@/Services/HttpServiceAbstract'
import { HttpProfileServiceInterface } from '@/Services/Interfaces/HttpProfileServiceInterface'
import type { IUserPasswordChange, IUserProfile } from '@/Types/IUser'

export class HttpProfileService extends HttpServiceAbstract implements HttpProfileServiceInterface {
    constructor(endpoint?: string) {
        super(endpoint)
    }
    async updateProfileInformation(user: IUserProfile): Promise<void> {
        try {
            await this.client.put('/user/profile-information', snakecaseKeys({ ...user }))
        } catch (e) {
            throw this.errorClassForThrow(e)
        }
    }

    async updateProfilePassword(change: IUserPasswordChange): Promise<void | never> {
        try {
            await this.client.put('/user/password', snakecaseKeys({ ...change }))
        } catch (e) {
            throw this.errorClassForThrow(e)
        }
    }
}
