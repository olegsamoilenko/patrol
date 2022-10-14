import { createRouter, createWebHistory } from "vue-router";
import routes from "./routes";
import { useAuthStore } from "@/stores/auth";

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const store = useAuthStore();
    console.log(store.authenticated);
    document.title = to.meta.title;
    if (to.meta.middleware === "guest") {
        console.log("guest");
        if (store.authenticated) {
            next({ name: "home" });
        } else {
            next();
        }
    } else if (to.meta.middleware === "auth" && to.meta.role === "user") {
        console.log("user");
        if (!store.authenticated) {
            next({ name: "login" });
        } else if (store.authenticated && store.user.roles[0].role === "none") {
            console.log("none");
            next({ name: "userNotActivated" });
        } else {
            next();
        }
    }  else if (to.meta.middleware === "auth" && to.meta.role === "admin") {
        console.log("admin");
        if (!store.authenticated) {
            next({ name: "login" });
        } else if (store.authenticated && store.user.roles[0].role === "none") {
            next({ name: "userNotActivated" });
        } else if (store.authenticated && store.user.roles[0].role !== "admin") {
            // TODO: redirect to page "У вас немає прав доступу"
        } else {
            next();
        }
    } else if (to.meta.middleware === "auth" && to.meta.role === "none") {
        if (store.authenticated && store.user.roles[0].role === "none") {
            next();
        }
    }
});

export default router;
