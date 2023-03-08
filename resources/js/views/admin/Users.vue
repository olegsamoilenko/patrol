<template>
    <v-layout column>
        <v-container fluid>
            <v-card>
                <v-card-title> Користувачі</v-card-title>
                <div v-if="isLoader">
                    <Loader />
                </div>
                <div v-else>
                    <FilterUsers />
                    <CardUser />
                    <PaginationUsers />
                </div>
            </v-card>
        </v-container>
    </v-layout>
</template>

<script setup>
import {onMounted, computed,} from "vue";
import { useUserRolesStore } from "@/stores/userRoles";
import { useUsersStore } from "@/stores/users";
import FilterUsers from "@/components/admin/users/FilterUsers.vue";
import CardUser from "@/components/admin/users/CardUser.vue";
import PaginationUsers from "@/components/admin/users/PaginationUsers.vue";
import Loader from "@/components/Loader.vue";


const usersStore = useUsersStore();
const userRolesStore = useUserRolesStore();


onMounted(() => {
    usersStore.getUsers()
    userRolesStore.getUserRoles();
    console.log(usersStore.paginationUsers);
});


const isLoader = computed(() => {
    return !usersStore.isLoadedUsers || !userRolesStore.isLoadedUserRoles;
});

</script>

<style>
.v-messages {
    flex: 0 1 auto;
}
</style>
