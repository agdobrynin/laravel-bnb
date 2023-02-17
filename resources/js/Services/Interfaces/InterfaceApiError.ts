import type { IApiError } from '@/Types/IApiError'

export interface InterfaceApiError {
    get backendMessage(): string;

    get backendResponse(): IApiError|undefined;

    get httpStatus(): number;

    get requestErrorMessage(): string;
}
