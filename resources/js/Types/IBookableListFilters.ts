export interface IBookableListFilters {
    /**
     * BookableCategoryUuid
     */
    bookableCategoryId?: string,
    priceMin?: number,
    priceMax?: number,
    priceWeekendMin?: number,
    priceWeekendMax?: number,
}
