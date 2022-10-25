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
        } else if ((store.authenticated && store.user.roles.find(r => r.slug === "none") || (store.authenticated && Object.values(store.user.roles).length === 0))) {
            console.log("none");
            next({ name: "userNotActivated" });
        } else {
            console.log('store.user.roles', Object.values(store.user.roles).length);
            next();
        }
    }  else if (to.meta.middleware === "auth" && to.meta.role === "admin") {
        console.log("auth admin", store.user);
        if (!store.authenticated) {
            console.log(111)
            next({ name: "login" });
        } else if ((store.authenticated && store.user.roles.find(r => r.slug === "none") || (store.authenticated  && Object.values(store.user.roles).length === 0))) {
            console.log(222)
            next({ name: "userNotActivated" });
        } else if (store.authenticated && !store.user.roles.find(r => r.slug === "admin")) {
            console.log(333)
            next({ name: "home" });
            // next({ name: "userNotPermission" });
        } else {
            console.log(444)
            next();
        }
    } else if (to.meta.middleware === "auth" && to.meta.role === "none") {
        console.log("auth none", store.user);
        if ((store.authenticated && store.user.roles.find(r => r.slug === "none") || (store.authenticated  && Object.values(store.user.roles).length === 0))) {
            next();
        } else {
            next({ name: "home" });
        }
    }
});

export default router;
