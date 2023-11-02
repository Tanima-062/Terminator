
const routes = [
    {
        path: '/customer-company-admin/:slug?',
        name: 'customer-company-admin',
        redirect: { name: 'customer-company-admin-index' },
        component: () => import('../pages/RouteContainer.vue'),
        children: [
            {
                path: 'create',
                name: 'customer-company-admin-create',
                component: () => import('../pages/CompanyAdmin/Create.vue'),
                meta: { title: 'Add New Customer Company Admin' }
            },
            {
                path: 'edit/:id',
                name: 'customer-company-admin-edit',
                component: () => import('../pages/CompanyAdmin/Edit.vue'),
                meta: { title: 'Edit Customer Company Admin' }
            },
            {
                path: ':id',
                name: 'customer-company-admin-show',
                component: () => import('../pages/CompanyAdmin/Show.vue'),
                meta: { title: 'Customer Company Admin Detail' }
            },
            {
                path: '',
                name: "customer-company-admin-index",
                meta: { title: 'Customer Company Admin' },
                component: () => import('../pages/CompanyAdmin/Index.vue'),
            }
        ]
    },

]

export default routes;
