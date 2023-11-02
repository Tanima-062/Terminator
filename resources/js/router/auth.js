
// import Login from '../pages/Auth/Login.vue'

const routes = [
    {
        path: '/login',
        name: 'login',
        component: ()=>import('../pages/Auth/Login.vue'),
        meta: { middleware: 'guest', 'title': 'Login' }
    },
    {
        path: '/reset-password',
        name: 'reset-password',
        component: () => import('../pages/Auth/ForgotPassword.vue'),
        meta: {
            middleware: 'guest',
            'title': 'ForgotPassword'
        }
    },
    {
        path: '/update-password',
        name: 'reset-password-form',
        component: () => import('../pages/Auth/ResetPasswordForm.vue'),
        meta: {
            middleware: 'guest',
            'title': 'Reset Password'
        }
    },
    {
        path: '/register-invitation',
        name: 'register-invitation',
        component: () => import('../pages/Auth/CustomerCompanyAdmin/RegisterInvitationForm.vue'),
        meta: {
            middleware: 'guest',
            'title': 'Invitation'
        }
    },
    {
        path: '/register-invitation-success',
        name: 'register-invitation-success',
        component: () => import('../pages/Auth/CustomerCompanyAdmin/InvitaionSuccess.vue'),
        meta: {
            middleware: 'guest',
            'title': 'Invitation Success'
        }
    },
]

export default routes;
