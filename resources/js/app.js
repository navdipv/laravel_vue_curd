import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store/index';

const app = createApp(App);

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

app.use(router)
.use(ZiggyVue, Ziggy)
.use(VueSweetalert2)
.use(Notifications)
.use(store)
.use(LoadingPlugin);


app.mount('#app');

// Check if a token exists before dispatching getProfile
if (localStorage.getItem('token')) {
    store.dispatch('getProfile');
  }
