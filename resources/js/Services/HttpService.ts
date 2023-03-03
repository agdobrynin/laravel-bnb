import type { AxiosError, AxiosResponse } from 'axios'
import axios from 'axios'
import snakecaseKeys from 'snakecase-keys'

import { ApiError } from '@/Services/ApiError'
import { ApiValidationError } from '@/Services/ApiValidationError'
import type { HttpServiceInterface } from '@/Services/Interfaces/HttpServiceInterface'
import type { IBookableItem } from '@/Types/IBookableItem'
import type { IBookableList } from '@/Types/IBookableList'
import type { IBookingAvailability } from '@/Types/IBookingAvailability'
import type { IBookingByReviewKey } from '@/Types/IBookingByReviewKey'
import { ICalculateBooking } from '@/Types/ICalculateBooking'
import { ICheckout, ICheckoutSuccess } from '@/Types/ICheckout'
import type { IReviewCollection, IReviewItem } from '@/Types/IReviewExistItem'
import type { IReviewResourceExist } from '@/Types/IReviewResourceExist'

export default class HttpService implements HttpServiceInterface {
    private readonly endpoint: string

    constructor(endpoint?: string) {
        this.endpoint = endpoint === undefined
            ? import.meta.env.VITE_API_ENDPOINT
            : this.endpoint = endpoint
    }

    async getBookables(): Promise<IBookableList | never> {
        try {
            return <IBookableList>((await axios.get(`${this.endpoint}/bookables`)).data)
        } catch (reason) {
            throw new ApiError(<AxiosError>reason)
        }
    }

    async getBookable(id: string): Promise<IBookableItem | never> {
        try {
            return <IBookableItem>((await axios.get(`${this.endpoint}/bookables/${id}`)).data)
        } catch (reason) {
            throw new ApiError(<AxiosError>reason)
        }
    }

    async checkBookableAvailability(id: string, start: string, end: string): Promise<IBookingAvailability | never> {
        try {
            const config = { params: { start, end } }

            return <IBookingAvailability>((await axios.get(
                `${this.endpoint}/bookables/${id}/availability`,
                config
            )).data)
        } catch (reason) {
            throw this.errorClassForThrow(reason)
        }
    }


    async calculateBooking(id: string, start: string, end: string): Promise<ICalculateBooking | never> {
        try {
            const config = { params: { start, end } }

            return <ICalculateBooking>((await axios.get(
                `${this.endpoint}/bookables/${id}/calculate`,
                config
            )).data)
        } catch (reason) {
            throw this.errorClassForThrow(reason)
        }
    }

    async getBookableReviews(id: string): Promise<IReviewCollection | never> {
        try {
            return <IReviewCollection>((await axios.get(`${this.endpoint}/bookables/${id}/reviews`)).data)
        } catch (reason) {
            throw this.errorClassForThrow(reason)
        }
    }

    async getReview(id: string): Promise<boolean | never> {
        try {
            const result: AxiosResponse<IReviewResourceExist> = await axios.get(`${this.endpoint}/reviews/${id}`)

            return result.data.data.hasReview
        } catch (reason) {
            const error = <AxiosError>reason

            if (error.response?.status === 404) {
                return false
            }

            throw new ApiError(error)
        }
    }


    async getBookingByReviewKey(id: string): Promise<IBookingByReviewKey | never> {
        try {
            return <IBookingByReviewKey>((await axios.get(`${this.endpoint}/booking-by-review/${id}`)).data)
        } catch (reason) {
            throw this.errorClassForThrow(reason)
        }
    }

    async storeReview(review: IReviewItem): Promise<boolean | never> {
        try {
            const result: AxiosResponse<IReviewResourceExist> = await axios.post(`${this.endpoint}/reviews`, review)

            return result.data.data.hasReview
        } catch (reason) {
            throw this.errorClassForThrow(reason)
        }
    }

    async booking(checkout: ICheckout): Promise<ICheckoutSuccess | never>
    {
        try {
            // api get all keys as snake case mode
            return <ICheckoutSuccess>((await axios.post(`${this.endpoint}/checkout`, snakecaseKeys(checkout))).data)
        } catch (reason) {
            throw this.errorClassForThrow(reason)
        }
    }

    private errorClassForThrow(reason: unknown): ApiValidationError | ApiError {
        const error = <AxiosError>reason

        return (error.response?.status === 422) ? new ApiValidationError(error) : new ApiError(error)
    }
}
