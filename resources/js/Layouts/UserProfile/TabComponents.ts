import { ComponentPublicInstance, defineAsyncComponent, ref } from 'vue'

export enum tabKey {
    profileUpdate = 'profile',
    passwordUpdate = 'password',
    newReviews = 'reviews',
}

export const mapTabs: {[key in tabKey]: string} = {
    [tabKey.profileUpdate]: 'Update user profile',
    [tabKey.passwordUpdate]: 'Change current user password',
    [tabKey.newReviews]: 'Leave review',
}

export const errorTabLoader = ref<string | null>(null)

export const mapComponents: {[key in tabKey]: ComponentPublicInstance} = {
    [tabKey.profileUpdate]: defineAsyncComponent(() => import('@/Layouts/UserProfile/ProfileUpdate.vue')
        .catch(e => errorTabLoader.value = e)),
    [tabKey.passwordUpdate]: defineAsyncComponent (() => import('@/Layouts/UserProfile/PasswordUpdate.vue')
        .catch(e => errorTabLoader.value = e)),
    [tabKey.newReviews]: defineAsyncComponent (() => import('@/Layouts/UserProfile/NewReviews.vue')
        .catch(e => errorTabLoader.value = e)),
}
