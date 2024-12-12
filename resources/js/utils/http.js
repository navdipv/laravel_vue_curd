import axios from "axios";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

import store from "@/store";

// Create an Axios instance
const http = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL, // Adjust this to your API base URL
    headers: {
        "Content-Type": "application/json",
    },
});

// Add a request interceptor
http.interceptors.request.use(
    function (config) {
        store.dispatch('showLoader');
        // Example: Inserting JWT token into the header of every request
        const token = localStorage.getItem("token");
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    function (error) {
        setTimeout(() => {
            store.dispatch('hideLoader');
        }, 500);
        return Promise.reject(error);
    }
);

// Add a response interceptor
http.interceptors.response.use(
    function (response) {
        if (response.data.message) {
            toast.success(response.data.message, {
                autoClose: 4000,
            });
        }

        setTimeout(() => {
            store.dispatch('hideLoader');
        }, 500);
        return response;
    },
    function (error) {
        // Example: Handling 401 errors globally
        if (error.response && (error.response.status === 401 || error.response.status === 400 || error.response.status === 500 || error.response.status === 403)) {
            toast.error(error.response.data.error, {
                autoClose: 4000,
            });
        }
        setTimeout(() => {
            store.dispatch('hideLoader');
        }, 500);
        return Promise.reject(error);
    }
);

export default http;
