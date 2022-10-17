<template>
        <v-app>
            <v-app-bar app color="black">
                <template #image>
                    <v-img
                        gradient="to top right, rgba(69,71,58,1), rgba(96,102,68,1)"
                    ></v-img>
                </template>
                <template v-slot:prepend>
                    <v-app-bar-nav-icon
                        variant="text"
                        @click.stop="drawer = !drawer"
                    ></v-app-bar-nav-icon>
                </template>
<!--                <v-app-bar-nav-icon-->
<!--                    variant="text"-->
<!--                    @click.stop="drawer = !drawer"-->
<!--                ></v-app-bar-nav-icon>-->

                <v-app-bar-title>Патруль ДФТГ-1</v-app-bar-title>

                <v-spacer></v-spacer>

                <v-btn
                    @click.prevent="logout"
                >Вийти</v-btn
                >
            </v-app-bar>

            <v-navigation-drawer app v-model="drawer" bottom temporary>
                    <v-list-item
                        v-for="(route, i) in routeList"
                        :key="i"
                        :value="route.name"
                        active-color=""
                        :to="route.path"
                    >
                        <v-list-item-title
                            v-text="route.meta.title"
                        ></v-list-item-title>
                    </v-list-item>
            </v-navigation-drawer>

            <v-main>
                    <slot></slot>
            </v-main>

            <v-bottom-navigation app >
                <v-btn
                    class="ma-2"
                    color="blue"
                    icon="mdi:mdi-plus"
                    to="/add-event"
                ><span>Додати подію</span><v-icon>mdi:mdi-plus</v-icon></v-btn>
            </v-bottom-navigation>

            <v-footer app>
                <v-row justify="end" align="center">
                    <v-btn
                        class="ma-2"
                        color="blue"
                        icon="mdi:mdi-plus"
                        to="/add-event"
                    ></v-btn>
                </v-row>

            </v-footer>

        </v-app>

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
            route.meta.layout === "Authenticated" &&
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

<style scoped></style>
