import { createRouter, createWebHistory } from 'vue-router';
// Define your routes here
const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('../Pages/Home.vue'), // Corrected dynamic import
        meta: { requiresAuth: true }
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('../Pages/Auth/Login.vue'), // Corrected dynamic import
        meta: { guestOnly: true }
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('../Pages/Auth/Register.vue'), // Corrected dynamic import
        meta: { guestOnly: true }
    },
    // Categories Routes
    {
        path: '/categories',
        name: 'categories-index',
        component: () => import('../Pages/Categories/Index.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/categories/create',
        name: 'categories-create',
        component: () => import('../Pages/Categories/Create.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/categories/edit/:id',
        name: 'categories-edit',
        component: () => import('../Pages/Categories/Edit.vue'),
        meta: { requiresAuth: true },
        props: true
    },
    // // Products Routes
    {
        path: '/products',
        name: 'products-index',
        component: () => import('../Pages/Products/Index.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/products/create',
        name: 'products-create',
        component: () => import('../Pages/Products/Create.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/products/edit/:id',
        name: 'products-edit',
        component: () => import('../Pages/Products/Edit.vue'),
        meta: { requiresAuth: true },
        props: true
    }
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

// Navigation Guard
router.beforeEach((to, from, next) => {

    const isAuthenticated = !!localStorage.getItem('token');

    if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
        next({ name: 'login' });
    } else if (to.matched.some(record => record.meta.guestOnly) && isAuthenticated) {
        next({ name: 'home' });  // Redirect authenticated users to home
    } else {
        next();
    }
});

router.afterEach(() => {
})


export default router;
