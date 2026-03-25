export const dashboardRoutes = [
    {
        path: '/dashboard',
        component: () => import('@/layouts/CompanyLayout.vue'),
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'workspace.dashboard',
                component: () => import('@/views/workspace/DashboardView.vue'),
            },
            {
                path: 'users',
                name: 'workspace.users',
                component: () => import('@/views/workspace/users/UserView.vue'),
            }
        ]
    }
]

