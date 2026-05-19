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
  // Check if the route or any of its parent routes require authentication
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)

  if (requiresAuth) {
    const authStore = useAuthStore()

    if (!authStore.user) {
      await authStore.fetchUser()
    }

    if (!authStore.user) {
      return { name: 'login' }
    }

  // Role-Based Access Control (RBAC) Guard
  // Check if any matched route record requires an 'admin' role
  const requiresAdmin = to.matched.some(record => record.meta.requiresAdmin);
  if(requiresAdmin){
    const user = authStore.user;

    if(!user.companies){
      return { name: 'workspace.dashboard' }
    }

    // Find the currently active company record
    const activeCompany = user.companies.find((company: any) => {
        return company.id === user.current_company_id
    });

    // Access the loaded pivot relationship string safely
    const currentRole = activeCompany?.pivot?.role?.name?.toLowerCase();
    if(currentRole !== 'admin'){
      console.warn(`Access denied: User is a '${currentRole}' in this workspace.`);
      return { name: 'workspace.dashboard' }
    }
  }
  }

  return true
})

export default router
