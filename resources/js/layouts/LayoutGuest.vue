<template>
        <v-app>
                <v-app-bar app color="black" prominent>
                    <template #image>
                        <v-img
                            v-if="theme.global.name.value === 'light'"
                            gradient="var(--v-app-bar-gradient-light)"
                        ></v-img>
                        <v-img
                            v-else
                            gradient="var(--v-app-bar-gradient-dark)"
                        ></v-img>
                    </template>
                    <v-toolbar-title>Патруль ДФТГ-1</v-toolbar-title>

                    <v-btn icon class="mr-4" @click="toggleTheme">
                        <!--                    {{ theme }}-->
                        <v-icon size="30" v-if="theme.global.name.value === 'dark'"
                        >mdi:mdi-weather-sunny</v-icon
                        >
                        <v-icon size="30" v-else>mdi:mdi-weather-night</v-icon>
                    </v-btn>

                    <v-btn
                        to="/login"
                    >Логін</v-btn
                    >
                    <v-btn
                        to="/register"
                    >Реєстрація</v-btn
                    >
                </v-app-bar>

                <v-main>
                    <slot></slot>
                </v-main>
        </v-app>
</template>

<script setup>
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
</script>

<style scoped></style>
