
const routes = [
    {
        path: '/contact-data-records/:slug?',
        name: 'contact-data-records',
        redirect: { name: 'contact-data-records-leads-index' },
        component: () => import('../pages/RouteContainer.vue'),
        children: [
            {
                path: 'create',
                name: 'contact-data-records-create',
                component: () => import('../pages/ContactDataRecords/Create.vue'),
                meta: { title: 'Contact data records' }
            },
            {
                path: 'edit/:id',
                name: 'contact-data-records-edit',
                component: () => import('../pages/ContactDataRecords/Edit.vue'),
                meta: { title: 'Contact data records' }
            },
            {
                path: 'detail/:id',
                name: 'contact-data-records-show',
                component: () => import('../pages/ContactDataRecords/Show/Record.vue'),
            },
            {
                path: ':id/history',
                name: 'contact-data-records-show-history',
                component: () => import('../pages/ContactDataRecords/Show/History.vue'),
                meta: { title: 'Contact data records' },
            },
            {
                path: 'leads',
                name: "contact-data-records-leads-index",
                meta: { title: 'Contact data records' },
                component: () => import('../pages/ContactDataRecords/leads/Index.vue'),
            },
            {
                path: 'termin',
                name: "contact-data-records-termin-index",
                meta: { title: 'Contact data records' },
                component: () => import('../pages/ContactDataRecords/termin/Index.vue'),
            },
            {
                path: 'all',
                name: "contact-data-records-all-index",
                meta: { title: 'Contact data records' },
                component: () => import('../pages/ContactDataRecords/all/Index.vue'),
            },
        ]
    },

]

export default routes;
