import type { InterfaceApiError } from '@/Services/Interfaces/InterfaceApiError'
import { InterfaceApiValidationError } from '@/Services/Interfaces/InterfaceApiValidationError'
import type { IBookableItem } from '@/Types/IBookableItem'
import type { IBookableList } from '@/Types/IBookableList'
import type { IBookingAvailability } from '@/Types/IBookingAvailability'
import type { IBookingByReviewKey } from '@/Types/IBookingByReviewKey'
import type { IReviewCollection } from '@/Types/IReviewExistItem'
import type { IReviewItem } from '@/Types/IReviewExistItem'
import type { IReviewResourceExist } from '@/Types/IReviewResourceExist'

export interface InterfaceHttpService {
    /**
     * Get list of bookable items
     */
    getBookables(): Promise<IBookableList | InterfaceApiError>

    /**
     * Get bookable item by id
     */
    getBookable(id: string): Promise<IBookableItem | InterfaceApiError>

    /**
     * Check ability booking between dates
     */
    checkBookableAvailability(id: string, start: string, end: string): Promise<IBookingAvailability | InterfaceApiError | InterfaceApiValidationError>

    /**
     * Get collection of review for bookable item
     */
    getBookableReviews(id: string): Promise<IReviewCollection | InterfaceApiError>

    getReview(id: string): Promise<boolean|InterfaceApiError>

    getBookingByReviewKey(id: string): Promise<IBookingByReviewKey | InterfaceApiError>

    storeReview(review: IReviewItem): Promise<IReviewResourceExist | InterfaceApiError | InterfaceApiValidationError>
}
