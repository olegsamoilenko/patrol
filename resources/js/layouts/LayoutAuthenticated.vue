<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a
                    class="navbar-brand"
                    href="https://techvblogs.com/blog/spa-authentication-laravel-9-sanctum-vue3-vite"
                    target="_blank"
                    >TechvBlogs</a
                >
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div id="navbarNavDropdown" class="collapse navbar-collapse">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <router-link :to="{ name: 'home' }" class="nav-link"
                                >Home
                                <span class="sr-only"
                                    >(current)</span
                                ></router-link
                            >
                        </li>
                    </ul>
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
                </div>
            </div>
        </nav>
        <main class="mt-3">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";

const store = useAuthStore();
const router = useRouter();

async function logout() {
    console.log(111)
    await axios.post("/logout").then(({ data }) => {
        store.signOut();
        router.push({ name: "login" });
    });
}
</script>

<style scoped></style>
