import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store/index';

const app = createApp(App);
import { useLoading } from 'vue-loading-overlay'

// Route in Vue
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

// Toast
import Notifications from '@kyvg/vue3-notification';
import Vue3Toasity from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';


// Loader
import { LoadingPlugin } from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';

// Sweet Alert
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const $loading = useLoading({
    loader: 'bars',
    transition:"fade",
    opacity:"0.7",
    "lock-scroll" : true,
    color: "#EC1F27"
});

let loader = null;


app.use(router)
.use(ZiggyVue, Ziggy)
.use(VueSweetalert2)
.use(Notifications)
.use(store)
.use(LoadingPlugin);


app.mount('#app');
