
const routes = [
    {
        path: '/customer-companies/:slug?',
        name: 'customer-company',
        redirect: { name: 'customer-company-index' },
        component: () => import('../pages/RouteContainer.vue'),
        children: [
            {
                path: 'create',
                name: 'customer-company-create',
                component: () => import('../pages/Company/Create.vue'),
                meta: { title: 'Customer Company' }
            },
            {
                path: 'edit/:id',
                name: 'customer-company-edit',
                component: () => import('../pages/Company/Edit.vue'),
                meta: { title: 'Customer Company' }
            },
            {
                path: ':id',
                name: 'customer-company-show',
                component: () => import('../pages/Company/Show.vue'),
                meta: { title: 'Customer Company' }
            },
            {
                path: '',
                name: "customer-company-index",
                meta: { title: 'Customer Company' },
                component: () => import('../pages/Company/Index.vue'),
            }
        ]
    },

]

export default routes;
