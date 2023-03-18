import { IUserProfile } from '@/Types/IUser'

export interface HttpProfileServiceInterface {
    updateProfileInformation(user: IUserProfile): Promise<void | never>
}
