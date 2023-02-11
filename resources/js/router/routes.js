// const Home = () => import("@/views/Home.vue");

const routes = [

    // Guest =================================================

    {
        path: "/login",
        name: "login",
        component: () => import("@/views/auth/Login.vue"),
        meta: {
            title: `Login`,
            layout: 'Guest',
            middleware: "guest",
        },
    },
    {
        path: "/register",
        name: "register",
        component: () => import("@/views/auth/Register.vue"),
        meta: {
            title: `Register`,
            layout: 'Guest',
            middleware: "guest",
        },
    },

    // Authenticated ========================================--

    {
        path: "/",
        name: "home",
        component: () => import("@/views/Home.vue"),
        meta: {
            title: `Головна`,
            layout: 'Authenticated',
            middleware: "auth",
            role: "Користувач",
        },
        props: {
            prependIcon: 'mdi:mdi-home',
        },
    },
    {
        path: "/add-incident",
        name: "add-incident",
        component: () => import("@/views/AddIncident.vue"),
        meta: {
            title: `Додати Подію`,
            layout: 'Authenticated',
            middleware: "auth",
            role: "Користувач",
        },
        props: {
            prependIcon: 'mdi:mdi-plus',
        },
    },
    {
        path: "/incidents",
        name: "incidents",
        component: () => import("@/views/Incidents.vue"),
        meta: {
            title: `Події`,
            layout: 'Authenticated',
            middleware: "auth",
            role: "Користувач",
        },
        props: {
            prependIcon: 'mdi:mdi-eye',
            color: 'error',
        },
    },

    // Admin =====================================================

    {
        path: "/admin",
        name: "homeAdmin",
        component: () => import("@/views/admin/Home.vue"),
        meta: {
            title: `Головна Адмін`,
            layout: 'Authenticated',
            middleware: "auth",
            role: "Адміністратор",
        },
        props: {
            prependIcon: 'mdi:mdi-home',
        },
    },

    {
        path: "/admin/users",
        name: "paginatedUsers",
        component: () => import("@/views/admin/Users.vue"),
        meta: {
            title: `Користувачі`,
            layout: 'Authenticated',
            middleware: "auth",
            role: "Адміністратор",
        },
        props: {
            prependIcon: 'mdi:mdi-account-group',
        },
    },

    {
        path: "/admin/districts",
        name: "cityDistricts",
        component: () => import("@/views/admin/Districts.vue"),
        meta: {
            title: `Райони Міста`,
            layout: 'Authenticated',
            middleware: "auth",
            role: "Адміністратор",
        },
        props: {
            prependIcon: 'mdi:mdi-city-variant-outline',
        },
    },

    {
        path: "/admin/roles",
        name: "userRoles",
        component: () => import("@/views/admin/UserRoles.vue"),
        meta: {
            title: `Ролі Користувачів`,
            layout: 'Authenticated',
            middleware: "auth",
            role: "Супер Адміністратор",
        },
        props: {
            prependIcon: 'mdi:mdi-account-details',
        },
    },

    {
        path: "/admin/permissions",
        name: "userPermissions",
        component: () => import("@/views/admin/UserPermissions.vue"),
        meta: {
            title: `Дозволи Користувачів`,
            layout: 'Authenticated',
            middleware: "auth",
            role: "Супер Адміністратор",
        },
        props: {
            prependIcon: 'mdi:mdi-account-details',
        },
    },

    // Error-page ===============================================

    {
        path: "/user-not-activated",
        name: "userNotActivated",
        component: () => import("@/views/error-page/UserNotActivated.vue"),
        meta: {
            type: "error",
            layout: 'Authenticated',
            middleware: "auth",
            title: `User Not Activated`,
        },
    },

    {
        path: "/user-not-permission",
        name: "userNotPermission",
        component: () => import("@/views/error-page/UserNotPermission.vue"),
        meta: {
            type: "error",
            layout: 'Authenticated',
            middleware: "auth",
            title: `User Not Permission`,
        },
        props: {
            prependIcon: 'mdi:mdi-home',
        },
    },

];

export default routes;
