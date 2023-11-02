const routes = [
    {
        path: "/internal-users/:slug?",
        name: "internal-users",
        redirect: { name: "internal-user-index" },
        component: () => import("../pages/RouteContainer.vue"),
        children: [
            {
                path: "create",
                name: "internal-user-create",
                component: () => import("../pages/InternalUser/Create.vue"),
                meta: { title: "Internal user create" },
            },
            {
                path: ":id",
                name: "internal-user-show",
                component: () => import("../pages/InternalUser/Show.vue"),
                meta: { title: "Internal User Details" },
            },
            {
                path: "edit/:id",
                name: "internal-user-edit",
                component: () => import("../pages/InternalUser/Edit.vue"),
                meta: { title: "Internal User Edit" },
            },
            {
                path: "",
                name: "internal-user-index",
                meta: { title: "Internal User" },
                component: () => import("../pages/InternalUser/Index.vue"),
            },
        ],
    },
];
export default routes;
