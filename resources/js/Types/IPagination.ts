export interface IPagination {
    meta: IPaginationMeta
}

export interface IPaginationMeta {
    links: IPaginationLink[],
    current_page: number,
    from: number,
    to: number,
    total: number,
    last_page: number,
    per_page: number,
}

export interface IPaginationLink {
    active: boolean,
    label: string,
    url: string,
}

export interface IPaginationData {
    currentPage: number,
    total: number,
    perPage: number,
    lastPage: number,
}
