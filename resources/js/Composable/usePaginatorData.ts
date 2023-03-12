import router from '@/router'
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
    const newQueryString = { ...router.currentRoute.value.query, ...{ page: String(page) } }
    await router.replace({ query: newQueryString })
}
