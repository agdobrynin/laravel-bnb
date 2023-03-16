import router from '@/router'

export const useBuildQueryString = async (params: any): Promise<void> => {
    const newQueryString = { ... { ... router.currentRoute.value.query } , ...{ ... params } }
    await router.replace({ query: newQueryString })
}
