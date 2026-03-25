import { createRouter, createWebHistory } from 'vue-router'
import { authRoutes } from './modules/auth';
import { dashboardRoutes } from './modules/workspace';
import { useAuthStore } from '@/stores/auth';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    ...authRoutes,
    ...dashboardRoutes
  ],
})

router.beforeEach(async (to) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)

  if (requiresAuth) {
    const authStore = useAuthStore()

    if (!authStore.user) {
      await authStore.fetchUser()
    }

    if (!authStore.user) {
      return { name: 'login' } 
    }
  }

  return true 
})

export default router
