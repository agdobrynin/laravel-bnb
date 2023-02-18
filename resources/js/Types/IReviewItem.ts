export interface IReviewItem {
    name: string,
    description: string,
    rating: number,
    /**
     * Full date and time with format YYYY-MM-DDThh:mm:ss
     */
    createdAt: string,
}
