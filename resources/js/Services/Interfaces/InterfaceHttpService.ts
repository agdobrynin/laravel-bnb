import { InterfaceApiError } from '@/Services/Interfaces/InterfaceApiError'
import { IApiValidationError } from '@/Types/IApiValidationError'
import type { IBookableItem } from '@/Types/IBookableItem'
import { IBookableList } from '@/Types/IBookableList'
import { IBookingAvailability } from '@/Types/IBookingAvailability'
import { IReviewCollection } from '@/Types/IReviewItem'

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
    checkBookableAvailability(id: string, start: string, end: string): Promise<IBookingAvailability|InterfaceApiError|IApiValidationError>

    /**
     * Get collection of review for bookable item
     */
    getBookableReviews(id: string): Promise<IReviewCollection|InterfaceApiError>
}
