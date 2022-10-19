<template>
        <v-app>
                <v-app-bar app color="black" prominent>
                    <template #image>
                        <v-img
                            gradient="var(--v-app-bar-gradient)"
                        ></v-img>
                    </template>
                    <v-toolbar-title>Патруль ДФТГ-1</v-toolbar-title>

                    <v-spacer></v-spacer>

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

</script>

<style scoped></style>
