<template>
    <v-layout column>
        <v-container fluid>
            <v-card class="">
                <v-card-title>Home</v-card-title>
                <p class="">You name is  <b>{{store.user.name}}</b></p>
                <p class="">You email is  <b>{{store.user.email}}</b></p>
                <p v-if="!store.isEmptyUser" class="">You role is  <b v-for="role in store.user.roles" :key="role.id">{{role.role}}, </b></p>
            </v-card>
        </v-container>
    </v-layout>
</template>

<script setup>
import { useAuthStore } from "@/stores/auth";

const store = useAuthStore();

// export default {
//
// }


</script>

<style scoped>

</style>
