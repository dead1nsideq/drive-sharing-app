import {createRouter, createWebHistory} from 'vue-router'
import Login from "@/views/Login.vue";
import NotFound from "@/views/NotFound.vue";
import Home from "@/views/Home.vue";
import Map from "@/views/Map.vue";
import {useAuthStore} from "@/stores/auth.js";
import Location from "@/views/Location.vue";
import {useLocationStore} from "@/stores/location.js";
import Trip from "@/views/Trip.vue";
import Driver from "@/views/Driver.vue";
import Standby from "@/views/Standby.vue";
import Driving from "@/views/Driving.vue";


const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/login',
            name: 'login',
            meta: {requiresGuest: true},
            component: Login
        },
        {
            path: '/home',
            name: 'home',
            meta: {requiresAuth: true},
            component: Home
        },
        {
            path: '/location',
            name: 'location',
            meta: {requiresAuth: true},
            component: Location
        },
        {
            path: '/map',
            name: 'map',
            meta: {requiresAuth: true},
            component: Map
        },
        {
            path: '/trip',
            name: 'trip',
            meta: {requiresAuth: true},
            component: Trip
        },
        {
            path: '/driver',
            name: 'driver',
            meta: {requiresAuth: true},
            component: Driver
        },
        {
            path: '/standby',
            name: 'standby',
            meta: {requiresAuth: true},
            component: Standby
        },
        {
            path: '/driving',
            name: 'driving',
            meta: {requiresAuth: true},
            component: Driving
        },
        {
            path: '/:pathMatch(.*)',
            name: 'not-found',
            component: NotFound
        },
    ]
})

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    const locationStore = useLocationStore();
    await authStore.initialAuthStatusCheck();

    if (to.meta.requiresGuest && authStore.state.logged) {
        next({name: 'home'});
    } else if (to.meta.requiresAuth && !authStore.state.logged) {
        next({name: 'login'});
    } else if (to.name === 'map' && locationStore.destination.name === '') {
        next({name: 'location'});
    } else {
        next();
    }
})

export default router
