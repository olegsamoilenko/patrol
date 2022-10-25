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
                    У Вас немає прав доступу до цієї сторінки
                </v-alert>
            </v-card>
        </v-container>
    </v-layout>
</template>

<script>
export default {
    name: "UserNotPermission",
};
</script>

<style scoped></style>
