import type { IApiError } from '@/Types/IApiError'

export interface ApiErrorInterface {
    get httpStatus(): number;

    get requestError(): string;

    get apiError(): IApiError | undefined;
}
