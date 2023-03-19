import { ComponentPublicInstance, defineAsyncComponent, ref } from 'vue'

export enum tabKey {
    profileUpdate = 'profile',
    passwordUpdate = 'password',
}

export const mapTabs: {[key in tabKey]: string} = {
    [tabKey.profileUpdate]: 'User profile',
    [tabKey.passwordUpdate]: 'Change password',
}

export const errorTabLoader = ref<string | null>(null)

export const mapComponents: {[key in tabKey]: ComponentPublicInstance} = {
    [tabKey.profileUpdate]: defineAsyncComponent(() => import('@/Layouts/UserProfile/ProfileUpdate.vue')
        .catch(e => errorTabLoader.value = e)),
    [tabKey.passwordUpdate]: defineAsyncComponent (() => import('@/Layouts/UserProfile/PasswordUpdate.vue')
        .catch(e => errorTabLoader.value = e)),
}
