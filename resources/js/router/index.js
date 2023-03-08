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
        if (store.authenticated) {
            next({ name: "home" });
        } else {
            next();
        }
    } else if (to.meta.middleware === "auth" && to.meta.role === "Користувач") {
        if (!store.authenticated) {
            next({ name: "login" });
        } else if (store.authenticated && store.user.is_activated === 'Ні') {
            next({ name: "userNotActivated" });
        } else if (
            (store.authenticated &&
                store.user.roles.find((r) => r.name === "Користувач")) || (store.authenticated &&
                store.user.roles.find((r) => r.name === "Супер Адміністратор"))
        ) {
            next();
        } else {
            next({ name: "userNotPermission" });
        }
    } else if (to.meta.middleware === "auth" && to.meta.role === "Аналітик") {
        if (!store.authenticated) {
            next({ name: "login" });
        } else if (store.authenticated && store.user.is_activated === 'Ні') {
            next({ name: "userNotActivated" });
        } else if (
            (store.authenticated &&
                store.user.roles.find((r) => r.name === "Аналітик")) || (store.authenticated &&
                store.user.roles.find((r) => r.name === "Супер Адміністратор"))
        ) {
            next();
        } else {
            next({ name: "userNotPermission" });
        }
    }else if (to.meta.middleware === "auth" && to.meta.role === "Адміністратор") {
        if (!store.authenticated) {
            next({ name: "login" });
        } else if (store.authenticated && store.user.is_activated === 'Ні') {
            next({ name: "userNotActivated" });
        } else if (
            (store.authenticated &&
                store.user.roles.find((r) => r.name === "Адміністратор")) || (store.authenticated &&
                store.user.roles.find((r) => r.name === "Супер Адміністратор"))
        ) {
            next();
        } else {
            next({ name: "userNotPermission" });
        }
    } else if (to.meta.middleware === "auth" && to.meta.role === "Супер Адміністратор") {
        if (!store.authenticated) {
            next({ name: "login" });
        } else if (store.authenticated && store.user.is_activated === 'Ні') {
            next({ name: "userNotActivated" });
        } else if (
            (store.authenticated &&
                store.user.roles.find((r) => r.name === "Супер Адміністратор"))
        ) {
            next();
        } else {
            next({ name: "home" });
        }
    } else if (to.meta.middleware === "auth" && to.meta.type === "error") {
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
