import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import VueGoogleMaps from 'vue-google-maps-community-fork'

import App from './App.vue'
import router from './router'

import axios from "axios";


window.axios = axios
window.axios.defaults.baseURL= "http://localhost:8000/api";

// axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
axios.interceptors.request.use(
    config => {
        if (localStorage.getItem('token')) {
           config.headers['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
        }
        return config;
    },
    error => {
        return Promise.reject(error);
    }
);

const app = createApp(App)
// AIzaSyDQxV0TU2-f-976Ovp2-_C1Qxh2Z00lrd4
app.use(createPinia())
app.use(router)
app.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyDQxV0TU2-f-976Ovp2-_C1Qxh2Z00lrd4',
        libraries: 'places'
    },
})
app.mount('#app')
