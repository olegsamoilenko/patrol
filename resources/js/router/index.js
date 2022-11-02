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
    } else if (to.meta.middleware === "auth" && to.meta.role === "Користувач") {
        console.log("auth user", store.user);
        if (!store.authenticated) {
            next({ name: "login" });
        } else if (store.authenticated && store.user.is_activated === 'Ні') {
            console.log("NotActivated");
            next({ name: "userNotActivated" });
        } else if (
            (store.authenticated &&
                store.user.roles.find((r) => r.name === "Користувач")) || (store.authenticated &&
                store.user.roles.find((r) => r.name === "Супер Адміністратор"))
        ) {
            console.log('Все ок');
            next();

        } else {
            console.log('userNotPermission');
            // next({ name: "home" });
            next({ name: "userNotPermission" });
        }
    } else if (to.meta.middleware === "auth" && to.meta.role === "Адміністратор") {
        console.log("auth admin", store.user);
        if (!store.authenticated) {
            console.log('!store.authenticated');
            next({ name: "login" });
        } else if (store.authenticated && store.user.is_activated === 'Ні') {
            console.log('userNotActivated');
            next({ name: "userNotActivated" });
        } else if (
            (store.authenticated &&
                store.user.roles.find((r) => r.name === "Адміністратор")) || (store.authenticated &&
                store.user.roles.find((r) => r.name === "Супер Адміністратор"))
        ) {
            console.log('Все ок');
            next();
        } else {
            console.log('userNotPermission');
            // next({ name: "home" });
            next({ name: "userNotPermission" });
        }
    } else if (to.meta.middleware === "auth" && to.meta.role === "Супер Адміністратор") {
        console.log("auth admin", store.user);
        if (!store.authenticated) {
            console.log('!store.authenticated');
            next({ name: "login" });
        } else if (store.authenticated && store.user.is_activated === 'Ні') {
            console.log('userNotActivated');
            next({ name: "userNotActivated" });
        } else if (
            (store.authenticated &&
                store.user.roles.find((r) => r.name === "Супер Адміністратор"))
        ) {
            console.log('Все ок');
            next();
        } else {
            console.log('userNotPermission');
            next({ name: "home" });
            // next({ name: "userNotPermission" });
        }
    } else if (to.meta.middleware === "auth" && to.meta.type === "error") {
        console.log("auth error", store.user);
        if (to.name === "userNotActivated") {
            if (store.authenticated && store.user.is_activated === 'Ні') {
                next();
            } else {
                next({ name: "home" });
            }
        } else {
            next();
        }

    }
});

export default router;
