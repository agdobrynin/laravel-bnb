import { useBuildQueryString } from '@/Composable/useBuildQueryString'
import { IBookableListFilters } from '@/Types/IBookableListFilters'
import type { IPaginationData, IPaginationMeta } from '@/Types/IPagination'

export const usePaginatorData = (meta: IPaginationMeta | null): IPaginationData => {
    const {
        total = 1,
        last_page: lastPage = 1,
        current_page: currentPage = 1,
        per_page: perPage = 1,
    } = meta || {}

    return { total, lastPage, currentPage, perPage }
}

export const usePaginatorBuildQueryStringParamsInRouter = async (page: number): Promise<void> => {
    await useBuildQueryString({ page: String(page) })
}

export const useFiltersBuildQueryStringParamsInRouter = async (filters: IBookableListFilters): Promise<void> => {
    await useBuildQueryString(filters)
}
