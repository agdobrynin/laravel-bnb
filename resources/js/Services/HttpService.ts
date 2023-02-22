import type { AxiosError, AxiosResponse } from 'axios'
import axios from 'axios'

import { ApiError } from '@/Services/ApiError'
import { ApiValidationError } from '@/Services/ApiValidationError'
import type { InterfaceApiError } from '@/Services/Interfaces/InterfaceApiError'
import { InterfaceApiValidationError } from '@/Services/Interfaces/InterfaceApiValidationError'
import type { InterfaceHttpService } from '@/Services/Interfaces/InterfaceHttpService'
import type { IApiValidationError } from '@/Types/IApiValidationError'
import type { IBookableItem } from '@/Types/IBookableItem'
import type { IBookableList } from '@/Types/IBookableList'
import type { IBookingAvailability } from '@/Types/IBookingAvailability'
import type { IBookingByReviewKey } from '@/Types/IBookingByReviewKey'
import type { IReviewCollection, IReviewItem } from '@/Types/IReviewExistItem'
import type { IReviewResourceExist } from '@/Types/IReviewResourceExist'

export default class HttpService implements InterfaceHttpService {
    private readonly endpoint: string
    constructor(endpoint?:string) {
        if (endpoint === undefined) {
            //@ts-ignore
            this.endpoint = import.meta.env.VITE_API_ENDPOINT
        } else {
            this.endpoint = endpoint
        }
    }

    getBookables(): Promise<IBookableList | InterfaceApiError> {
        return axios
            .get(`${this.endpoint}/bookables`)
            .then((response: AxiosResponse<IBookableList>) => response.data)
            .catch((reason: AxiosError) => Promise.reject(new ApiError(reason)))
    }

    getBookable(id: string): Promise<IBookableItem | InterfaceApiError> {
        return axios
            .get(`${this.endpoint}/bookables/${id}`)
            .then((reason: AxiosResponse<IBookableItem>) => reason.data)
            .catch((reason: AxiosError) => Promise.reject(new ApiError(reason)))
    }

    checkBookableAvailability(id: string, start: string, end: string): Promise<IBookingAvailability | InterfaceApiError | InterfaceApiValidationError> {
        return axios
            .get(`${this.endpoint}/bookables/${id}/availability`, {
                params: {
                    start,
                    end,
                }
            })
            .then((response: AxiosResponse<IBookingAvailability>) => response.data)
            .catch((reason: AxiosError) => {
                const { response } = reason

                if (response?.status === 422) {
                    return Promise.reject(new ApiValidationError(<IApiValidationError>response.data))
                }

                return Promise.reject(new ApiError(reason))
            })
    }

    getBookableReviews(id: string): Promise<IReviewCollection | InterfaceApiError> {
        return axios
            .get(`${this.endpoint}/bookables/${id}/reviews`)
            .then((response: AxiosResponse<IReviewCollection>) => response.data)
            .catch((reason: AxiosError) => Promise.reject(new ApiError(reason)))
    }

    getReview(id: string): Promise<boolean | InterfaceApiError> {
        return axios
            .get(`${this.endpoint}/reviews/${id}`)
            .then((response: AxiosResponse<IReviewResourceExist>) => response.data.data.hasReview)
            .catch((reason: AxiosError) => {
                return reason.status === 404 ? Promise.reject(false) : Promise.reject(new ApiError(reason))
            })
    }

    getBookingByReviewKey(id: string): Promise<IBookingByReviewKey | InterfaceApiError> {
        return axios
            .get(`${this.endpoint}/booking-by-review/${id}`)
            .then((response: AxiosResponse<IBookingByReviewKey>) => response.data)
            .catch((reason: AxiosError) => Promise.reject(new ApiError(reason)))
    }

    storeReview(review: IReviewItem): Promise<IReviewResourceExist | InterfaceApiError | InterfaceApiValidationError> {
        return axios.post(`${this.endpoint}/reviews`, review)
            .then((response: AxiosResponse<IReviewResourceExist>) => response.data)
            .catch((reason: AxiosError<InterfaceApiError|IApiValidationError>) => {
                const { response } = reason

                if (response?.status === 422) {
                    return Promise.reject(new ApiValidationError(<IApiValidationError>response.data))
                }

                return Promise.reject(new ApiError(reason))
            })

    }
}
