<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Home</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">You are logged in as <b>{{store.user.email}}</b></p>
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

</style>
