<template>
    <v-card-text>
        <v-col cols="12">
            <v-pagination
                v-model="usersStore.paginationCurrentPage"
                :length="usersStore.paginationLastPage"
                :total-visible="5"
                @update:modelValue="onPageChange"
            ></v-pagination>
        </v-col>
    </v-card-text>
</template>

<script setup>
import { useUsersStore } from "@/stores/users";

const usersStore = useUsersStore();

// Pagination ======================================
function onPageChange() {
    usersStore.getUsers();
}
</script>

<style scoped>

</style>
