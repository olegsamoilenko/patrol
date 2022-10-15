<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <span class="text-amber-accent-3">Guest</span>
                >
            </div>
            <div class="d-flex">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <router-link :to="{ name: 'login' }" class="nav-link"
                            >Login</router-link
                        >
                    </li>
                    <li class="nav-item">
                        <router-link :to="{ name: 'register' }" class="nav-link"
                        >Register</router-link
                        >
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

</script>

<style scoped></style>
