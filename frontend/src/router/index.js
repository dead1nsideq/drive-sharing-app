import { createRouter, createWebHistory } from 'vue-router'
import Login from "@/views/Login.vue";
import NotFound from "@/views/NotFound.vue";


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: Login
    },
    {
      path: '/:pathMatch(.*)',
      name: 'not-found',
      component: NotFound
    },
  ]
})

export default router
