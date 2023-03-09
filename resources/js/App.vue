<template>
    <Layout>
        <router-view v-slot="{ Component }">
            <v-scale-transition>
                <component :is="Component"/>
            </v-scale-transition>
        </router-view>
    </Layout>

</template>

<script setup>
import Layout from '@/layouts/Layout.vue'
import {onBeforeMount} from 'vue'
import {useAuthStore} from "@/stores/auth";
// import {useFirebase} from "./firebase.js";

import {useChatsStore} from "@/stores/chats";

const chatsStore = useChatsStore();

// const firebase = useFirebase();

Echo.private('chat')
    .listen('ChatMessage', (e) => {
        console.log('e', e)
        chatsStore.getMessages();
    });

const store = useAuthStore();

onBeforeMount(() => {
    // firebase.firebaseInit()
    return axios
        .get("/api/user")
        .then(({data}) => {
            if (JSON.stringify(data.roles) !== JSON.stringify(store.user.roles) || data.is_activated !== store.user.is_activated) {
                store.signIn();
            }
        })
        .catch(() => {
            store.signOut();
        })
})

</script>

<style scoped></style>
