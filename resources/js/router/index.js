import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/login',
        name: 'login',
        component: () => import('../pages/Login.vue'),
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('../pages/Register.vue'),
    },
    {
        path: '/tasks',
        name: 'tasks',
        component: () => import('../pages/Tasks.vue'),
    },
    {
        path: '/',
        redirect: '/tasks',
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
