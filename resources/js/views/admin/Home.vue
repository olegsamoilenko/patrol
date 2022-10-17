<template>
    <div v-cloak class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Home Admin</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">You name is  <b>{{store.user.name}}</b></p>
                        <p class="mb-0">You email is  <b>{{store.user.email}}</b></p>
                        <p v-if="!store.isEmptyUser" class="mb-0">You role is  <b v-for="role in store.user.roles" :key="role.id">{{role.role}}, </b></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useAuthStore } from "@/stores/auth";

const store = useAuthStore();


</script>

<style scoped>
[v-cloak] {
    display:none!important;
}
</style>
