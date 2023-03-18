export interface IUser {
    email: string,
    name: string,
    isVerified: boolean
}

export interface IUserProfile {
    email: string,
    firstName: string,
    lastName: string,
}

export interface IUserRegister extends IUserProfile {
    password: string,
    passwordConfirmation: string,
}
