export interface IReviewItem {
    id: string,
    name?: string,
    description: string,
    rating: number,
    /**
     * Full date and time with format YYYY-MM-DDThh:mm:ss
     */
    createdAt: string,
}

export interface IReviewCollection {
    data: IReviewItem[],
}
