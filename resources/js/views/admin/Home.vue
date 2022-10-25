<template>
    <v-layout column>
        <v-container fluid>
            <v-card>
                <v-card-title> Кількість користувачів </v-card-title>
                <v-card-text v-if="loader">
                    <v-col cols="12" class="d-flex justify-center">
                        <v-progress-circular
                            :size="50"
                            indeterminate
                            color="green"
                        ></v-progress-circular>
                    </v-col>

                </v-card-text>
                <v-card-text v-else>
                    <v-row :class=" {'font-weight-bold': usersWithRoleNone > 0, 'text-warning':  usersWithRoleNone > 0 }">
                        <v-col cols="9"> Не активованих користувачів: </v-col>
                        <v-col cols="3"> {{usersWithRoleNone}} </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="9"> Адміністраторів: </v-col>
                        <v-col cols="3"> {{usersWithRoleAdmin}} </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="9"> Активованих користувачів: </v-col>
                        <v-col cols="3"> {{usersWithRoleUser}} </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="9"> Всього користувачів: </v-col>
                        <v-col cols="3"> {{ numberOfUsers }} </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-container>
    </v-layout>
</template>

<script setup>
import { onMounted, ref } from "vue";
// import { useAuthStore } from "@/stores/auth";
//
// const store = useAuthStore();
const loader = ref(false);
const numberOfUsers = ref(null);
const usersWithRoleNone = ref(null);
const usersWithRoleUser = ref(null);
const usersWithRoleAdmin = ref(null);

onMounted(() => {
    getUserStat();
});

async function getUserStat() {
    loader.value = true
    await axios
        .get("/get-user-statistics")
        .then(({ data }) => {
            numberOfUsers.value = data.numberOfUsers;
            usersWithRoleNone.value = data.usersWithRoleNone;
            usersWithRoleUser.value = data.usersWithRoleUser;
            usersWithRoleAdmin.value = data.usersWithRoleAdmin;
            console.log("data", data);

        })
        .catch(({ response }) => {
            console.log(response);
        })
        .finally(() => {
            loader.value = false
        });
}
</script>

<style scoped>

</style>
