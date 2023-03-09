export interface IUser {
    email: string,
    name: string,
    isVerified: boolean
}

export interface IUserRegister {
    email: string,
    name: string,
    password: string,
    passwordConfirmation: string,
}
