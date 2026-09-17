import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';

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
        meta: { requiresAuth: true },
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

router.beforeEach(async (to, from, next) => {
    // Если маршрут требует авторизации
    if (to.meta.requiresAuth) {
        try {
            await axios.get('/api/user');
            next();
        } catch (error) {
            next({ name: 'login' });
        }
    } else {
        next();
    }
});

export default router;
