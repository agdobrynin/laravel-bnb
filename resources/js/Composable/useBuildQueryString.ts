import router from '@/router'
import { IDictionary } from '@/Types/IDictionary'

export const useBuildQueryString = async (params: IDictionary): Promise<void> => {
    const newQueryString = { ... { ... router.currentRoute.value.query } , ...{ ... params } }
    await router.replace({ query: newQueryString })
}
