import axios from 'axios';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';


// Create an Axios instance
const http = axios.create({
    baseURL: 'http://laravel_vue_app.localhost/api/', // Adjust this to your API base URL
    headers: {
        'Content-Type': 'application/json'
    }
});

// Add a request interceptor
http.interceptors.request.use(
    function(config) {
        // Do something before request is sent
        // Example: Inserting JWT token into the header of every request
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    function(error) {
        // Do something with request error
        return Promise.reject(error);
    }
);

// Add a response interceptor
http.interceptors.response.use(
    function(response) {
        if(response.data.message){
            toast.success(response.data.message, {
                autoClose: 2000,
            });
        }

        // Any status code that lie within the range of 2xx cause this function to trigger
        return response;
    },
    function(error) {
        // Example: Handling 401 errors globally
        if (error.response && error.response.status === 401) {
            toast.error(error.response.data.error, {
                autoClose: 2000,
            });
        }
        return Promise.reject(error);
    }
);

export default http;
