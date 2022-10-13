/* Guest Component */
const Login = () => import('@/components/auth/Login.vue')
const Register = () => import('@/components/auth/Register.vue')

/* Layouts */
const LayoutAuthenticated = () => import('@/layouts/LayoutAuthenticated.vue')

/* Authenticated Component */
const Home = () => import('@/views/Home.vue')


const routes = [
    {
        name: "login",
        path: "/login",
        component: Login,
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        name: "register",
        path: "/register",
        component: Register,
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },
    {
        path: "/",
        component: LayoutAuthenticated,
        meta: {
            middleware: "auth"
        },
        children: [
            {
                name: "home",
                path: '/',
                component: Home,
                meta: {
                    title: `Home`
                }
            }
        ]
    }
  ];

export default routes
