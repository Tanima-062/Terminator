
const routes = [
    {
        path: '/termins/:slug?',
        name: 'termins',
        redirect: { name: 'termins-index' },
        component: () => import('../pages/RouteContainer.vue'),
        children: [
            {
                path: 'edit/:id',
                name: 'termins-edit',
                component: () => import('../pages/Termins/Edit.vue'),
                meta: { title: 'Contact data records' }
            },
            {
                path: 'detail/:id',
                name: 'termins-show',
                component: () => import('../pages/Termins/Show/Record.vue'),
            },
            {
                path: ':id/history',
                name: 'termins-show-history',
                component: () => import('../pages/Termins/Show/History.vue'),
                meta: { title: 'Contact data records' },
            },
            {
                path: 'termin',
                name: "termins-index",
                // meta: { title: 'Contact data records' },
                component: () => import('../pages/Termins/Index.vue'),
            },
        ]
    },

]

export default routes;
