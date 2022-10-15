/* Error-page Component */
const UserNotActivated = () =>
    import("@/views/error-page/UserNotActivated.vue");

/* Guest Component */
const LayoutGuest = () => import("@/layouts/LayoutGuest.vue");
const Login = () => import("@/components/auth/Login.vue");
const Register = () => import("@/components/auth/Register.vue");

/* Layouts */
const LayoutAuthenticated = () => import("@/layouts/LayoutAuthenticated.vue");

/* Authenticated Component */
const Home = () => import("@/views/Home.vue");

/* Admin Component */
const LayoutAdmin = () => import("@/layouts/LayoutAdmin.vue");
const HomeAdmin = () => import("@/views/admin/Home.vue");

const routes = [

    // Guest =================================================

    {
        path: "/login",
        name: "login",
        component: Login,
        meta: {
            title: `Login`,
            layout: 'Guest',
            middleware: "guest",
        },
    },
    {
        path: "/register",
        name: "register",
        component: Register,
        meta: {
            title: `Register`,
            layout: 'Guest',
            middleware: "guest",
        },
    },

    // Authenticated ========================================---

    {
        path: "/",
        name: "home",
        component: Home,
        meta: {
            title: `Home`,
            layout: 'Authenticated',
            middleware: "auth",
            role: "user",
        },
    },

    // Admin =====================================================

    {
        path: "/admin",
        name: "homeAdmin",
        component: HomeAdmin,
        meta: {
            title: `Home Admin`,
            layout: 'Admin',
            middleware: "auth",
            role: "admin",
        },
    },

    // Error-page ===============================================

    {
        path: "/user-not-activated",
        name: "userNotActivated",
        component: UserNotActivated,
        meta: {
            middleware: "auth",
            title: `User Not Activated`,
            role: "none",
        },
    },


    // {
    //     path: "/",
    //     component: LayoutGuest,
    //     meta: {
    //         middleware: "guest",
    //     },
    //     children: [
    //         {
    //             name: "login",
    //             path: "/login",
    //             component: Login,
    //             meta: {
    //                 title: `Login`,
    //             },
    //         },
    //         {
    //             name: "register",
    //             path: "/register",
    //             component: Register,
    //             meta: {
    //                 title: `Register`,
    //             },
    //         },
    //     ],
    // },
    //
    // {
    //     path: "/",
    //     component: LayoutAuthenticated,
    //     meta: {
    //         middleware: "auth",
    //         role: "user",
    //     },
    //     children: [
    //         {
    //             name: "userNotActivated",
    //             path: "/user-not-activated",
    //             component: UserNotActivated,
    //             meta: {
    //                 role: "none",
    //                 title: `User Not Activated`,
    //             },
    //         },
    //         {
    //             name: "home",
    //             path: "/",
    //             component: Home,
    //             meta: {
    //                 title: `Home`,
    //             },
    //         },
    //     ],
    // },
    // {
    //     path: "/admin",
    //     component: LayoutAdmin,
    //     meta: {
    //         middleware: "auth",
    //         role: "admin",
    //     },
    //     children: [
    //         {
    //             name: "homeAdmin",
    //             path: "/admin",
    //             component: HomeAdmin,
    //             meta: {
    //                 title: `Home Admin`,
    //             },
    //         },
    //     ],
    // },
];

export default routes;
