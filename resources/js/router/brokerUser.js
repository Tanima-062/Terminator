
const routes = [
    {
        path: '/broker-users/:slug?',
        name: 'broker-user',
        redirect: { name: 'broker-user-index' },
        component: () => import('../pages/RouteContainer.vue'),
        children: [
            {
                path: 'create',
                name: 'broker-user-create',
                component: () => import('../pages/BrokerUser/Create.vue'),
                meta: { title: 'Broker User' }
            },
            {
                path: 'edit/:id',
                name: 'broker-user-edit',
                component: () => import('../pages/BrokerUser/Edit.vue'),
                meta: { title: 'Broker User' }
            },
            {
                path: ':id',
                name: 'broker-user-show',
                component: () => import('../pages/BrokerUser/Show.vue'),
                meta: { title: 'Broker User' }
            },
            {
                path: '',
                name: "broker-user-index",
                meta: { title: 'Broker User' },
                component: () => import('../pages/BrokerUser/Index.vue'),
            }
        ]
    },

]

export default routes;
