import type { AxiosError } from 'axios'
import axios from 'axios'

import { ApiError } from '@/Services/ApiError'
import { ApiValidationError } from '@/Services/ApiValidationError'
import type { InterfaceApiError } from '@/Services/Interfaces/InterfaceApiError'
import type { InterfaceHttpService } from '@/Services/Interfaces/InterfaceHttpService'
import { IApiValidationError } from '@/Types/IApiValidationError'
import type { IBookableItem } from '@/Types/IBookableItem'
import type { IBookableList } from '@/Types/IBookableList'
import { IBookingAvailability } from '@/Types/IBookingAvailability'

export default class HttpService implements InterfaceHttpService {
    constructor(private readonly endpoint?: string) {
        if (this.endpoint === undefined) {
            //@ts-ignore
            this.endpoint = import.meta.env.VITE_API_ENDPOINT
        }
    }

    getBookables(): Promise<IBookableList|InterfaceApiError> {
        return axios
            .get(`${this.endpoint}/bookables`)
            .then(response => <IBookableList>response.data)
            .catch((reason: AxiosError) => Promise.reject(new ApiError(reason)))
    }

    getBookable(id: string): Promise<IBookableItem|InterfaceApiError> {
        return axios
            .get(`${this.endpoint}/bookables/${id}`)
            .then(response => <IBookableItem>response.data)
            .catch((reason: AxiosError) => Promise.reject(new ApiError(reason)))
    }

    checkBookableAvailability(id: string, start: string, end: string): Promise<IBookingAvailability|InterfaceApiError|IApiValidationError> {
        return axios
            .get(`${this.endpoint}/bookables/${id}/availability`, {
                params: {
                    start,
                    end,
                }
            })
            .then((response) => <IBookingAvailability>response.data)
            .catch((reason: AxiosError) => {
                const { response } = reason

                if (response?.status === 422) {
                    return Promise.reject(new ApiValidationError(<IApiValidationError>response.data))
                }

                return Promise.reject(new ApiError(reason))
            })
    }
}
