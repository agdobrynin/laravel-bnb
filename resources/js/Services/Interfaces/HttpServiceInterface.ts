import type { ApiErrorInterface } from '@/Services/Interfaces/ApiErrorInterface'
import type { ApiValidationErrorInterface } from '@/Services/Interfaces/ApiValidationErrorInterface'
import type { IBookableItem } from '@/Types/IBookableItem'
import type { IBookableList } from '@/Types/IBookableList'
import type { IBookingAvailability } from '@/Types/IBookingAvailability'
import type { IBookingByReviewKey } from '@/Types/IBookingByReviewKey'
import type { IReviewCollection } from '@/Types/IReviewExistItem'
import type { IReviewItem } from '@/Types/IReviewExistItem'

export interface HttpServiceInterface {
    /**
     * Get list of bookable items
     * @throws ApiErrorInterface
     */
    getBookables(): Promise<IBookableList | never>

    /**
     * Get bookable item by id
     * @throws ApiErrorInterface
     */
    getBookable(id: string): Promise<IBookableItem | never>

    /**
     * Check ability booking between dates
     * @throws ApiErrorInterface|ApiValidationErrorInterface
     */
    checkBookableAvailability(id: string, start: string, end: string): Promise<IBookingAvailability | never>

    /**
     * Get collection of review for bookable item
     * @throws ApiErrorInterface
     */
    getBookableReviews(id: string): Promise<IReviewCollection | never>

    /**
     * @throws ApiErrorInterface
     */
    getReview(id: string): Promise<boolean> | never

    /**
     * @throws ApiErrorInterface
     */
    getBookingByReviewKey(id: string): Promise<IBookingByReviewKey | never>

    /**
     * @throws ApiErrorInterface|ApiValidationErrorInterface
     */
    storeReview(review: IReviewItem): Promise<boolean | never>
}
