export interface IUser {
    email: string,
    name: string,
    isVerified: boolean,
    newReviewCount: number,
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

export interface IUserPasswordChange {
    currentPassword: string,
    password: string,
    passwordConfirmation: string,
}
