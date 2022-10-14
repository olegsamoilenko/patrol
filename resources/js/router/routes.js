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
    {
        path: "/",
        component: LayoutGuest,
        meta: {
            middleware: "guest",
        },
        children: [
            {
                name: "login",
                path: "/login",
                component: Login,
                meta: {
                    title: `Login`,
                },
            },
            {
                name: "register",
                path: "/register",
                component: Register,
                meta: {
                    title: `Register`,
                },
            },
        ],
    },

    {
        path: "/",
        component: LayoutAuthenticated,
        meta: {
            middleware: "auth",
            role: "user",
        },
        children: [
            {
                name: "userNotActivated",
                path: "/user-not-activated",
                component: UserNotActivated,
                meta: {
                    role: "none",
                    title: `User Not Activated`,
                },
            },
            {
                name: "home",
                path: "/",
                component: Home,
                meta: {
                    title: `Home`,
                },
            },
        ],
    },
    {
        path: "/admin",
        component: LayoutAdmin,
        meta: {
            middleware: "auth",
            role: "admin",
        },
        children: [
            {
                name: "homeAdmin",
                path: "/admin",
                component: HomeAdmin,
                meta: {
                    title: `Home Admin`,
                },
            },
        ],
    },
];

export default routes;
