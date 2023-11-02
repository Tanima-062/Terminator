import { createRouter, createWebHistory } from 'vue-router'
import beforeEach from './beforeEach';
import auth from './auth'
import customerCompany from './customerCompany'
import organizationElement from './organizationElement'
import customerCompanyAdmin from './customerCompanyAdmin'
import broker from './broker'
import brokerUser from './brokerUser'
import intermediary from './intermediary'
import dashboard from './dashboard'
import internalUser from './internalUser'
import workflowSettings from './workflowSettings'

import ContactDataRecords from './contactDataRecords'
import termin from './termin'
import DashboardLayout from '../layouts/DashboardLayout.vue'


const routes = [
    ...auth,
    {
        path: '/',
        component: DashboardLayout,
        meta: { middleware: "auth" },
        children: [
            ...dashboard,
            ...customerCompany,
            ...customerCompanyAdmin,
            ...organizationElement,
            ...broker,
            ...brokerUser,
            ...ContactDataRecords,
            ...internalUser,
            ...workflowSettings,
            ...intermediary,
            ...termin
        ]
    },
    {
        path: '/403',
        name: '403',
        component: () => import('./../pages/403.vue'),
          meta: {
            // middleware: 'guest',
            'title': 'Invalid access'
        }
    },
    {
        path: '/:pathMatch(.*)*',
        name: '404',
        component: () => import('./../pages/404.vue'),
          meta: {
            // middleware: 'guest',
            'title': 'Not found'
        }
    },
    {
        path: '/404',
        name: 'not_found_guest',
        component: () => import('./../pages/404.vue'),
          meta: {
            middleware: 'guest',
            'title': 'Not found'
        }
    }
];

const router = createRouter({
    routes,
    history: createWebHistory()
});

router.beforeEach(beforeEach)

export default router;


