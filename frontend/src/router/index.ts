import { createRouter, createWebHistory } from 'vue-router'
import { authRoutes } from './modules/auth';
import { dashboardRoutes } from './modules/dashboard';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    ...authRoutes,
    ...dashboardRoutes
  ],
})

export default router
