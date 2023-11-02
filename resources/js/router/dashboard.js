
// import Dashboard from '../pages/dashboard/index.vue'

const routes = [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: ()=> import('../pages/Dashboard/Index.vue'),
        meta: {title: 'Dashboard'}
    }
]

export default routes;
