import { defineStore } from 'pinia'

import { HttpAuthService } from '@/Services/HttpAuthService'
import type { IUser, IUserProfile } from '@/Types/IUser'

interface IAuthState {
    user: IUser | null
}

export const useAuthStore = defineStore('auth', {
    state: (): IAuthState => ({
        user: null
    }),
    getters: {
        firstName: (state): string | null => state.user?.name.split(' ')[0] || null,

        lastName: (state): string | null => state.user?.name.split(' ')[1] || null,

        userProfile(): IUserProfile {
            return {
                firstName: this.firstName || '',
                lastName: this.lastName || '',
                email: this.user?.email || '',
            }
        },
    },
    actions: {
        async fetchUser() {
            this.user = await new HttpAuthService().fetchUser()
        }
    }
})
