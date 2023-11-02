
const routes = [
    {
        path: '/workflow-settings/:slug?',
        name: 'workflow-settings',
        component: () => import('../pages/RouteContainer.vue'),
        children: [
            {
                path: '',
                name: "workflow-settings-setps-and-costs",
                meta: { title: 'Workflow settings' },
                component: () => import('../pages/WorkflowSettings/StepsAndCost.vue'),
            }
        ]
    },

]

export default routes;
