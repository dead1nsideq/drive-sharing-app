import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

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

app.use(createPinia())
app.use(router)

app.mount('#app')
