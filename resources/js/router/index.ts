import ExampleOne from '@/Components/ExampleOne.vue'
import ExampleTwo from '@/Components/ExampleTwo.vue'
import {createRouter, createWebHistory, RouteRecordRaw} from 'vue-router'

const routes: RouteRecordRaw[] = [
    {
        name: 'home',
        path: '/',
        component: ExampleOne,
    },
    {
        name: 'two',
        path: '/two',
        component: ExampleTwo
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
