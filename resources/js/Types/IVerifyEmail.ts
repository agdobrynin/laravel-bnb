export interface IVerifyEmail {
    // User id
    id: string,
    // Secure hash string
    hash: string,
    // Expires unix timestamp
    expires: string,
    // Some secure params
    signature: string,
}
