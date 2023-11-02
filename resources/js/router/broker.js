
const routes = [
    {
        path: '/brokers/:slug?',
        name: 'broker',
        redirect: { name: 'broker-index' },
        component: () => import('../pages/RouteContainer.vue'),
        children: [
            {
                path: 'create',
                name: 'broker-create',
                component: () => import('../pages/Broker/Create.vue'),
                meta: { title: 'Broker' }
            },
            {
                path: 'edit/:id',
                name: 'broker-edit',
                component: () => import('../pages/Broker/Edit.vue'),
                meta: { title: 'Broker' }
            },
            {
                path: ':id',
                name: 'broker-show',
                component: () => import('../pages/Broker/Show.vue'),
                meta: { title: 'Broker' }
            },
            {
                path: '',
                name: "broker-index",
                meta: { title: 'Broker' },
                component: () => import('../pages/Broker/Index.vue'),
            }
        ]
    },

]

export default routes;
