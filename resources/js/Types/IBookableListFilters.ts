import { IDictionary } from '@/Types/IDictionary'

export interface IBookableListFilters extends IDictionary {
    /**
     * BookableCategoryUuid
     */
    bookableCategoryId?: string,
    priceMin?: number,
    priceMax?: number,
    priceWeekendMin?: number,
    priceWeekendMax?: number,
}
