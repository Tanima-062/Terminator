
const routes = [
    {
        path: '/organization-element/:slug?',
        name: 'organization-element',
        redirect: { name: 'organization-element-index' },
        component: () => import('../pages/RouteContainer.vue'),
        children: [
            {
                path: 'create',
                name: 'organization-element-create',
                component: () => import('../pages/OrganizationElement/Create.vue'),
                meta: { title: 'Organization Element' }
            },
            {
                path: 'edit/:id',
                name: 'organization-element-edit',
                component: () => import('../pages/OrganizationElement/Edit.vue'),
                meta: { title: 'Organization Element' }
            },
            {
                path: ':id',
                name: 'organization-element-show',
                component: () => import('../pages/OrganizationElement/Show.vue'),
                meta: { title: 'Organization Element' }
            },
            {
                path: '',
                name: "organization-element-index",
                meta: { title: 'Organization Element' },
                component: () => import('../pages/OrganizationElement/Index.vue'),
            }
        ]
    },

]

export default routes;
