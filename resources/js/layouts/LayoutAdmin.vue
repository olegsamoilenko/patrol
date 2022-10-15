<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <span class="text-amber-accent-3">Admin</span>
                >
            </div>
            <div class="d-flex">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a
                            id="navbarDropdownMenuLink"
                            class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            {{ store.user.name }}
                        </a>
                        <div
                            class="dropdown-menu dropdown-menu-end"
                            aria-labelledby="navbarDropdownMenuLink"
                        >
                            <a
                                class="dropdown-item"
                                @click.prevent="logout"
                            >Logout</a
                            >
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="mt-3">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { useAuthStore } from "@/stores/auth";

const store = useAuthStore();

</script>

<style scoped>

</style>
