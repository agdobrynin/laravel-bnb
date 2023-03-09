import { defineStore } from 'pinia'

import { HttpAuthService } from '@/Services/HttpAuthService'
import type { IUser } from '@/Types/IUser'

interface IAuthState {
    user: IUser | null
}

export const useAuthStore = defineStore('auth', {
    state: (): IAuthState => ({
        user: null
    }),
    actions: {
        async fetchUser() {
            this.user = await new HttpAuthService().fetchUser()
        }
    }
})
