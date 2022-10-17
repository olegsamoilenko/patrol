<template>
        <v-layout>
            <!--             <v-system-bar color="deep-purple darken-3"></v-system-bar>-->

            <v-app-bar color="black" prominent>
                <v-app-bar-nav-icon
                    variant="text"
                    @click.stop="drawer = !drawer"
                ></v-app-bar-nav-icon>

                <v-toolbar-title>Админ ДФТГ-1</v-toolbar-title>

                <v-spacer></v-spacer>

                <v-btn
                    @click.prevent="logout"
                >Logout</v-btn
                >
            </v-app-bar>

            <v-navigation-drawer v-model="drawer" bottom temporary>
                <v-list>
                    <v-list-item
                        v-for="(route, i) in routeList"
                        :key="i"
                        :value="route.name"
                        active-color="primary"
                        :to="route.path"
                    >
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
import { ref, computed } from "vue";
import { useAuthStore } from "@/stores/auth";
import routes from "@/router/routes";

const store = useAuthStore();

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

async function logout() {
    console.log(111);
    await axios.post("/logout").then(({ data }) => {
        store.signOut();
    });
}
</script>

<style scoped>

</style>
