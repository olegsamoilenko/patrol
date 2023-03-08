<template>
    <!--Filter Users------------------------------------------------------------>
    <v-card-text>
        <v-row>
            <v-col cols="12" sm="6">
                <v-select
                    v-model="usersStore.filterUsersParams.role"
                    :items="userRolesStore.allUsersRolesSelect"
                    :item-title="(item) => item.name"
                    :item-value="(item) => String(item.id)"
                    placeholder="Всі ролі"
                >
                </v-select>
            </v-col>
            <v-col cols="12" sm="6">
                <v-select
                    v-model="sortUsers"
                    :items="sortUsersMap"
                    placeholder="Cортувати За"
                >
                </v-select>
            </v-col>
            <v-col cols="12" class="relative">
                <v-text-field
                    v-model="usersStore.filterUsersParams.search"
                    variant="solo"
                    label="Пошук Користувача"
                    append-inner-icon="mdi:mdi-magnify"
                    single-line
                    hide-details
                ></v-text-field>
            </v-col>
            <v-col v-if="authStore.user.roles.find((r) => r.name === 'Супер Адміністратор')" cols="12"
                   class="relative">
                <v-switch v-model="usersStore.filterUsersParams.onlyTrashed" label="Тимчасово видалені" color="success"></v-switch>
            </v-col>
        </v-row>
    </v-card-text>
    <!--End Filter Users--------------------------------------------------------->
</template>

<script setup>
import { ref, watch, onBeforeMount } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useUsersStore } from "@/stores/users";
import { useUserRolesStore } from "@/stores/userRoles";

const authStore = useAuthStore();
const usersStore = useUsersStore();
const userRolesStore = useUserRolesStore();

onBeforeMount(() => {
    userRolesStore.getUserRoles();
});


const sortUsers = localStorage.getItem("sortUsersFilter") ? ref(localStorage.getItem("sortUsersFilter")) : ref('surnameAsc');
const sortUsersMap = ref([
    {value: "surnameAsc", title: "За Прізвищем   А...Я"},
    {value: "surnameDesc", title: "За Прізвищем   Я...А"},
    {value: "dateAsc", title: "За датою реєстрації   1...9"},
    {value: "dateDesc", title: "За датою реєстрації   9...1"},
]);

watch(sortUsers, () => {
    if (sortUsers.value === "surnameAsc") {
        usersStore.filterUsersParams.sortBy = "surname";
        usersStore.filterUsersParams.sortDirection = "ASC";
    } else if (sortUsers.value === "surnameDesc") {
        usersStore.filterUsersParams.sortBy = "surname";
        usersStore.filterUsersParams.sortDirection = "DESC";
    } else if (sortUsers.value === "dateAsc") {
        usersStore.filterUsersParams.sortBy = "created_at";
        usersStore.filterUsersParams.sortDirection = "ASC";
    } else if (sortUsers.value === "dateDesc") {
        usersStore.filterUsersParams.sortBy = "created_at";
        usersStore.filterUsersParams.sortDirection = "DESC";
    }
    localStorage.setItem("sortUsersFilter", sortUsers.value)
    usersStore.paginationCurrentPage = 1
    usersStore.getUsers()
});

watch(usersStore.filterUsersParams, () => {
    localStorage.setItem("usersRoleFilter", usersStore.filterUsersParams.role)
    usersStore.paginationCurrentPage = 1
    usersStore.getUsers()
});
</script>

<style scoped>

</style>
