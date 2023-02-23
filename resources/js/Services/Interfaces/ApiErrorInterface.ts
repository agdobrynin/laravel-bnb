import type { IApiError } from '@/Types/IApiError'

export interface ApiErrorInterface {
    get backendMessage(): string;

    get backendResponse(): IApiError|undefined;

    get httpStatus(): number;

    get requestErrorMessage(): string;
}
