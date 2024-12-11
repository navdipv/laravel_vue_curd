import { createRouter, createWebHistory } from 'vue-router';

// Import your component files here
import Home from '../Pages/Home.vue';
import Login from '../Pages/Auth/Login.vue';
import Register from '../Pages/Auth/Register.vue';

// Define your routes here
const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/register',
        name: 'register',
        component: Register
    }
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

export default router;
