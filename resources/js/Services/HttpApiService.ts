import type { AxiosError, AxiosResponse } from 'axios'
import snakecaseKeys from 'snakecase-keys'

import { ApiError } from '@/Services/ApiError'
import { HttpServiceAbstract } from '@/Services/HttpServiceAbstract'
import type { HttpApiServiceInterface } from '@/Services/Interfaces/HttpApiServiceInterface'
import type { IBookableItem } from '@/Types/IBookableItem'
import type { IBookableList } from '@/Types/IBookableList'
import type { IBookingAvailability } from '@/Types/IBookingAvailability'
import type { IBookingByReviewKey } from '@/Types/IBookingByReviewKey'
import { ICalculateBooking } from '@/Types/ICalculateBooking'
import { ICheckout, ICheckoutSuccess } from '@/Types/ICheckout'
import type { IReviewCollection, IReviewItem } from '@/Types/IReviewExistItem'
import type { IReviewResourceExist } from '@/Types/IReviewResourceExist'

export default class HttpApiService extends HttpServiceAbstract implements HttpApiServiceInterface {
    constructor(endpoint?: string) {
        super(endpoint)
        this.client.defaults.baseURL = `${this.endpoint}/api`
    }

    async getBookables(): Promise<IBookableList | never> {
        try {
            return <IBookableList>((await this.client.get('/bookables')).data)
        } catch (reason) {
            throw new ApiError(<AxiosError>reason)
        }
    }

    async getBookable(id: string): Promise<IBookableItem | never> {
        try {
            return <IBookableItem>((await this.client.get(`/bookables/${id}`)).data)
        } catch (reason) {
            throw new ApiError(<AxiosError>reason)
        }
    }

    async checkBookableAvailability(id: string, start: string, end: string): Promise<IBookingAvailability | never> {
        try {
            const config = { params: { start, end } }

            return <IBookingAvailability>((await this.client.get(
                `/bookables/${id}/availability`,
                config
            )).data)
        } catch (reason) {
            throw this.errorClassForThrow(reason)
        }
    }


    async calculateBooking(id: string, start: string, end: string): Promise<ICalculateBooking | never> {
        try {
            const config = { params: { start, end } }

            return <ICalculateBooking>((await this.client.get(
                `/bookables/${id}/calculate`,
                config
            )).data)
        } catch (reason) {
            throw this.errorClassForThrow(reason)
        }
    }

    async getBookableReviews(id: string): Promise<IReviewCollection | never> {
        try {
            return <IReviewCollection>((await this.client.get(`/bookables/${id}/reviews`)).data)
        } catch (reason) {
            throw this.errorClassForThrow(reason)
        }
    }

    async getReview(id: string): Promise<boolean | never> {
        try {
            const result: AxiosResponse<IReviewResourceExist> = await this.client.get(`/reviews/${id}`)

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
            return <IBookingByReviewKey>((await this.client.get(`/booking-by-review/${id}`)).data)
        } catch (reason) {
            throw this.errorClassForThrow(reason)
        }
    }

    async storeReview(review: IReviewItem): Promise<boolean | never> {
        try {
            const result: AxiosResponse<IReviewResourceExist> = await this.client.post('/reviews', review)

            return result.data.data.hasReview
        } catch (reason) {
            throw this.errorClassForThrow(reason)
        }
    }

    async booking(checkout: ICheckout): Promise<ICheckoutSuccess | never> {
        try {
            // api get all keys as snake case mode
            return <ICheckoutSuccess>(
                (await this.client.post('/checkout', snakecaseKeys(checkout))).data
            )
        } catch (reason) {
            throw this.errorClassForThrow(reason)
        }
    }
}
