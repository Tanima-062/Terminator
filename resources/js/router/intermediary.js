
const routes = [
    {
        path: '/intermediaries/:slug?',
        name: 'intermediary',
        redirect: { name: 'intermediary-index' },
        component: () => import('../pages/RouteContainer.vue'),
        children: [
            {
                path: 'create',
                name: 'intermediary-create',
                component: () => import('../pages/Intermediary/Create.vue'),
                meta: { title: 'Intermediary' }
            },
            {
                path: 'edit/:id',
                name: 'intermediary-edit',
                component: () => import('../pages/Intermediary/Edit.vue'),
                meta: { title: 'Intermediary' }
            },
            {
                path: ':id',
                name: 'intermediary-show',
                component: () => import('../pages/Intermediary/Show.vue'),
                meta: { title: 'Intermediary' }
            },
            {
                path: '',
                name: "intermediary-index",
                meta: { title: 'Intermediary' },
                component: () => import('../pages/Intermediary/Index.vue'),
            }
        ]
    },

]

export default routes;
