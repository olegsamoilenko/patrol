<template>
    <v-app>
        <v-app-bar app color="black">
            <template #image>
                <v-img
                    v-if="theme.global.name.value === 'light'"
                    gradient="var(--v-app-bar-gradient-light)"
                ></v-img>
                <v-img v-else gradient="var(--v-app-bar-gradient-dark)"></v-img>
            </template>

            <v-app-bar-nav-icon
                variant="text"
                @click.stop="drawer = !drawer"
            ></v-app-bar-nav-icon>

            <v-app-bar-title>Патруль ДФТГ-1</v-app-bar-title>

            <v-btn icon class="mr-3" @click="toggleTheme">
                <v-icon size="30" v-if="theme.global.name.value === 'dark'"
                    >mdi:mdi-weather-sunny</v-icon
                >
                <v-icon size="28" v-else>mdi:mdi-weather-night</v-icon>
            </v-btn>

            <v-btn icon="mdi:mdi-dots-vertical">
                <v-icon>mdi:mdi-dots-vertical</v-icon>
                <v-menu activator="parent">
                    <v-list>
                        <v-list-item @click.prevent="logout">
                            <template #prepend>
                                <v-icon icon="mdi:mdi-logout"></v-icon>
                            </template>
                            <v-list-item-title>Вийти</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-btn>
        </v-app-bar>

        <v-navigation-drawer v-model="drawer" app temporary>
            <v-list
                v-if="
                    Object.values(store.user).length !== 0 &&
                    (store.user.roles.find((r) => r.name === 'Користувач') ||
                        store.user.roles.find(
                            (r) => r.name === 'Супер Адміністратор'
                        ))
                "
            >
                <v-list-subheader>Меню</v-list-subheader>
                <v-list-item
                    v-for="(route, i) in routeListMain"
                    :key="i"
                    :value="route"
                    :to="route.path"
                >
                    <template #prepend>
                        <v-icon :icon="route.props.prependIcon"></v-icon>
                    </template>

                    <v-list-item-title
                        v-text="route.meta.title"
                    ></v-list-item-title>
                </v-list-item>
            </v-list>
            <v-list
                v-if="
                    Object.values(store.user).length !== 0 &&
                    (store.user.roles.find((r) => r.name === 'Адміністратор') ||
                        store.user.roles.find(
                            (r) => r.name === 'Супер Адміністратор'
                        ))
                "
            >
                <v-list-subheader>Адмін</v-list-subheader>
                <v-list-item
                    v-for="(route, i) in routeListAdmin"
                    :key="i"
                    :value="route"
                    :to="route.path"
                >
                    <template #prepend>
                        <v-icon :icon="route.props.prependIcon"></v-icon>
                    </template>

                    <v-list-item-title
                        v-text="route.meta.title"
                    ></v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-main>
            <slot></slot>
        </v-main>

        <v-footer app>
            <v-row justify="end" align="center">
                <v-btn
                    class="ma-2"
                    color="green"
                    icon="mdi:mdi-plus"
                    to="/add-incident"
                ></v-btn>
            </v-row>
        </v-footer>
    </v-app>
</template>

<script setup>
import { ref, computed } from "vue";
import { useAuthStore } from "@/stores/auth";
import routes from "@/router/routes";
import { useTheme } from "vuetify";

const theme = useTheme();

function toggleTheme() {
    if (theme.global.name.value === "light") {
        theme.global.name.value = "dark";
        localStorage.setItem("theme", "dark");
    } else {
        theme.global.name.value = "light";
        localStorage.setItem("theme", "light");
    }
}

const drawer = ref(false);

const routeListMain = computed(() =>
    routes.filter(
        (route) =>
            route.meta &&
            route.meta.middleware === "auth" &&
            route.meta.layout === "Authenticated" &&
            route.meta.role === "Користувач" &&
            (store.user.roles.find(r => r.name === route.meta.role) || store.user.roles.find(r => r.name === 'Супер Адміністратор')) &&
            route.meta.type !== "error"
    )
);

const routeListAdmin = computed(() =>
    routes.filter(
        (route) =>
            route.meta &&
            route.meta.middleware === "auth" &&
            route.meta.layout === "Authenticated" &&
            (route.meta.role === "Адміністратор" || route.meta.role === "Супер Адміністратор") &&
            (store.user.roles.find(r => r.name === route.meta.role) || store.user.roles.find(r => r.name === 'Супер Адміністратор')) &&
            route.meta.type !== "error"
    )
);

const store = useAuthStore();

async function logout() {
    console.log(111);
    await axios.post("/logout").then(({ data }) => {
        store.signOut();
    });
}
</script>

<style scoped></style>
