import {createRouter, createWebHistory} from 'vue-router'
import Login from "@/views/Login.vue";
import NotFound from "@/views/NotFound.vue";
import Home from "@/views/Home.vue";
import axios from "axios";


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
            path: '/:pathMatch(.*)',
            name: 'not-found',
            component: NotFound
        },
    ]
})

router.beforeEach((to, from) => {
    // axios.get('/user').then(
    //     () => {
    //         console.log('FROM AUTH STORE CAN BE LOGGED')
    //         if (to.meta.requiresGuest) {
    //             next({name: 'home'});
    //         } else {
    //             console.log('else')
    //             next();
    //         }
    //     }).catch(() => {
    //     localStorage.removeItem('token')
    //     console.error('FROM AUTH STORE CANNOT BE LOGGED');
    //     if (to.meta.requiresAuth) {
    //         next({name: 'login'});
    //     } else {
    //         console.log('else')
    //         next();
    //     }
    // })

    if (to.name === 'login') {
        return true
    }

    if (!localStorage.getItem('token')) {
        return {
            name: 'login'
        }
    }

    checkTokenAuthenticity()
})

const checkTokenAuthenticity = () => {
    axios.get('/user').then((response) => {
    })
        .catch((error) => {
            localStorage.removeItem('token')
            router.push({
                name: 'login'
            })
        })

}


export default router
