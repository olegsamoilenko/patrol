import { createRouter, createWebHistory } from "vue-router";
import routes from "./routes";
import { useAuthStore } from "@/stores/auth";

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const store = useAuthStore();
    document.title = to.meta.title;
    if (to.meta.middleware === "guest") {
        console.log("guest", store.user);
        if (store.authenticated) {
            next({ name: "home" });
        } else {
            next();
        }
    } else if (to.meta.middleware === "auth" && to.meta.role === "user") {
        console.log("auth user", store.user);
        if (!store.authenticated) {
            next({ name: "login" });
        } else if (store.authenticated && store.user.roles.find(r => r.role === "none")) {
            console.log("none");
            next({ name: "userNotActivated" });
        } else {
            next();
        }
    }  else if (to.meta.middleware === "auth" && to.meta.role === "admin") {
        console.log("auth admin", store.user);
        if (!store.authenticated) {
            console.log(111)
            next({ name: "login" });
        } else if (store.authenticated && store.user.roles.find(r => r.role === "none")) {
            console.log(222)
            next({ name: "userNotActivated" });
        } else if (store.authenticated && !store.user.roles.find(r => r.role === "admin")) {
            console.log(333)
            // TODO: redirect to page "У вас немає прав доступу"
        } else {
            console.log(444)
            next();
        }
    } else if (to.meta.middleware === "auth" && to.meta.role === "none") {
        console.log("auth none", store.user);
        if (store.authenticated && store.user.roles.find(r => r.role === "none")) {
            next();
        } else {
            next({ name: "home" });
        }
    }
});

export default router;
