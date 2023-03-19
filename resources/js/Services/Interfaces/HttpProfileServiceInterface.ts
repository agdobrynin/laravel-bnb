import { IUserPasswordChange, IUserProfile } from '@/Types/IUser'

export interface HttpProfileServiceInterface {
    updateProfileInformation(user: IUserProfile): Promise<void | never>

    updateProfilePassword(change: IUserPasswordChange): Promise<void | never>
}
