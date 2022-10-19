<template>
    <v-app>
        <v-app-bar app color="black" prominent>
            <template #image>
                <v-img gradient="var(--v-app-bar-gradient)"></v-img>
            </template>
            <!--                <template v-slot:prepend>-->
            <!--                    <v-app-bar-nav-icon-->
            <!--                        variant="text"-->
            <!--                        @click.stop="drawer = !drawer"-->
            <!--                    ></v-app-bar-nav-icon>-->
            <!--                </template>-->
            <v-app-bar-nav-icon
                variant="text"
                @click.stop="drawer = !drawer"
            ></v-app-bar-nav-icon>

            <v-app-bar-title>Патруль ДФТГ-1</v-app-bar-title>

            <v-btn @click.prevent="logout">Вийти</v-btn>
        </v-app-bar>

        <v-navigation-drawer app v-model="drawer" bottom temporary>
            <v-list>
                <v-list-subheader>Menu</v-list-subheader>
                <v-list-item
                    v-for="(route, i) in routeList"
                    :key="i"
                    class="list-item"
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

        <v-bottom-navigation app>
            <v-btn class="ma-2" color="blue" icon="mdi:mdi-plus" to="/add-event"
                ><span>Додати подію</span><v-icon>mdi:mdi-plus</v-icon></v-btn
            >
        </v-bottom-navigation>

        <v-footer app>
            <v-row justify="end" align="center">
                <v-btn
                    class="ma-2"
                    color="green"
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

<style scoped>
/*.list-item:hover {*/
/*    color: var(--v-navigation-drawer-list-item-title-hover-color);*/
/*}*/
</style>
