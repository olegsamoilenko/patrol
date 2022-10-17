<template>
    <v-layout>
        <v-container fluid>
            <v-card class="mx-auto" max-width="600px">
                <v-alert
                    icon="mdi:mdi-cloud-alert"
                    prominent
                    type="error"
                    variant="text"
                >
                    Ваш обліковий запис ще не активовано. Спробуйте трохи
                    пізніше
                </v-alert>
            </v-card>
        </v-container>
    </v-layout>
</template>

<script>
export default {
    name: "UserNotActivated",
};
</script>

<style scoped></style>
