export interface IReviewItem {
    id: string,
    description: string,
    rating: number,
}

export interface IReviewExistItem extends IReviewItem {
    name?: string,
    /**
     * Full date and time with format YYYY-MM-DDThh:mm:ss
     */
    createdAt: string,
}

export interface IReviewCollection {
    data: IReviewExistItem[],
}
