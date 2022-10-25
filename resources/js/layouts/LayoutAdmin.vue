<template>
        <v-layout>
            <v-app-bar app color="black" prominent>

                <template #image>
                    <v-img v-if="appBarColor === 'black'" gradient="var(--v-app-bar-gradient-light)"></v-img>
                    <v-img v-if="appBarColor === 'white'" gradient="var(--v-app-bar-gradient-dark)"></v-img>
                </template>

                <template v-slot:prepend>
                    <v-app-bar-nav-icon
                        variant="text"
                        @click.stop="drawer = !drawer"
                    ></v-app-bar-nav-icon>
                </template>

                <v-toolbar-title>Админ ДФТГ-1</v-toolbar-title>

                <template v-slot:append>
                    <v-btn
                        icon
                        @click="toggleTheme"
                        class="align-self-center mr-2"
                    >
                        <!--                    {{ theme }}-->
                        <v-icon size="30" v-if="theme.global.name.value === 'dark'">mdi:mdi-weather-sunny</v-icon>
                        <v-icon size="30" v-else>mdi:mdi-weather-night</v-icon>
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
                </template>
            </v-app-bar>

            <v-navigation-drawer v-model="drawer" bottom temporary>
                <v-list>
                    <v-list-subheader>Menu</v-list-subheader>
                    <v-list-item
                        v-for="(route, i) in routeList"
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
        </v-layout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useAuthStore } from "@/stores/auth";
import routes from "@/router/routes";
import { useTheme } from "vuetify";

const theme = useTheme();
const appBarColor = ref(null)

onMounted(() => {
    appBarColor.value = theme.global.name.value === "dark" ? "white" : "black";
})

function toggleTheme() {
    if (theme.global.name.value === 'myCustomTheme') {
        theme.global.name.value = "dark";
        localStorage.setItem('theme', "dark");
        appBarColor.value = "white";
    } else {
        theme.global.name.value = "myCustomTheme";
        localStorage.setItem('theme', "myCustomTheme");
        appBarColor.value = "black";
    }
}

const drawer = ref(false);

const routeList = computed(() =>
    routes.filter(
        (route) =>
            route.meta &&
            route.meta.middleware === "auth" &&
            route.meta.layout === "Admin" &&
            // store.user.roles.find(r => r.role === route.meta.role) &&
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

<style scoped>

</style>
