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
                    <v-row :class=" {'font-weight-bold': notActivatedUsersCount > 0, 'text-warning':  notActivatedUsersCount > 0 }">
                        <v-col cols="9"> Не активованих користувачів: </v-col>
                        <v-col cols="3"> {{notActivatedUsersCount}} </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="9"> Адміністраторів: </v-col>
                        <v-col cols="3"> {{usersWithRoleAdminCount }} </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="9"> Користувачів: </v-col>
                        <v-col cols="3"> {{usersWithRoleUserCount}} </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="9"> Всього користувачів: </v-col>
                        <v-col cols="3"> {{ usersCount }} </v-col>
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
const usersCount = ref(null);
const notActivatedUsersCount = ref(null);
const usersWithRoleUserCount = ref(null);
const usersWithRoleAdminCount = ref(null);

onMounted(() => {
    getUserStat();
});

async function getUserStat() {
    loader.value = true
    await axios
        .get("/admin/get-user-statistics")
        .then(({ data }) => {
            usersCount.value = data.usersCount;
            notActivatedUsersCount.value = data.notActivatedUsersCount;
            usersWithRoleUserCount.value = data.usersWithRoleUserCount;
            usersWithRoleAdminCount.value = data.usersWithRoleAdminCount;
                  })
        .catch((error) => {
            console.log('error', error);
        })
        .finally(() => {
            loader.value = false
        });
}
</script>

<style scoped>

</style>
