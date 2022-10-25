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
            role: "user",
        },
        props: {
            prependIcon: 'mdi:mdi-home',
        },
    },
    {
        path: "/add-event",
        name: "add-event",
        component: () => import("@/views/AddEvent.vue"),
        meta: {
            title: `Додати Подію`,
            layout: 'Authenticated',
            middleware: "auth",
            role: "user",
        },
        props: {
            prependIcon: 'mdi:mdi-plus',
        },
    },
    {
        path: "/events",
        name: "events",
        component: () => import("@/views/Events.vue"),
        meta: {
            title: `Події`,
            layout: 'Authenticated',
            middleware: "auth",
            role: "user",
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
            title: `Головна Админ`,
            layout: 'Authenticated',
            middleware: "auth",
            role: "admin",
        },
        props: {
            prependIcon: 'mdi:mdi-home',
        },
    },

    {
        path: "/admin/users",
        name: "paginatedUsers",
        component: () => import("@/views/admin/PaginatedUsers.vue"),
        meta: {
            title: `Користувачі`,
            layout: 'Authenticated',
            middleware: "auth",
            role: "admin",
        },
        props: {
            prependIcon: 'mdi:mdi-home',
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
            role: "none",
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
            role: "user",
        },
    },

];

export default routes;
