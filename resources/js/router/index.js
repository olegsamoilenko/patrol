import { createRouter, createWebHistory } from "vue-router";
import routes from "./routes";

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// router.beforeEach((to, from, next) => {
//     document.title = to.meta.title
//     if (to.meta.middleware == "guest") {
//         if (store.state.auth.authenticated) {
//             next({ name: "dashboard" })
//         }
//         next()
//     } else {
//         if (store.state.auth.authenticated) {
//             next()
//         } else {
//             next({ name: "login" })
//         }
//     }
// })

export default router;
