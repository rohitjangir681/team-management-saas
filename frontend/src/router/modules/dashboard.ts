export const dashboardRoutes = [
    {
        path: '/dashboard',
        component: () => import('@/layouts/DashboardLayout.vue'),
        children: [
            {
                path: '',
                name: 'dashboard',
                component: () => import('@/views/dashboard/DashboardView.vue'),
            },
            {
                path: 'users',
                name: 'users',
                component: () => import('@/views/dashboard/users/UserView.vue'),
            }
        ]
    }
]

