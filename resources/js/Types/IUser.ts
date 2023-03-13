export interface IUser {
    email: string,
    name: string,
    isVerified: boolean
}

export interface IUserRegister {
    email: string,
    firstName: string,
    lastName: string,
    password: string,
    passwordConfirmation: string,
}
