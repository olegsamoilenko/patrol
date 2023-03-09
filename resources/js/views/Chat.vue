<template>
    <v-layout column>
        <v-container fluid>
                <v-col class="mb-3">Чат</v-col>
                <div v-if="isLoader">
                    <Loader />
                </div>
                <div v-else>
                    <v-card
                        v-for="message in chatsStore.allMessages"
                        :key="message.id"
                        rounded="3"
                        class="mb-3 message"
                    >
                        <v-col>
                            {{ message.user.name }} {{ message.user.surname }}
                        </v-col>
                        <v-col>
                            {{ message.message }}
                        </v-col>
                        <v-col class="date">
                            {{ humanizeDate(message.created_at) }}
                        </v-col>

                    </v-card>
                    <v-card position="fixed" class="test" elevation="0">
                            <v-form @submit.prevent="handleSubmit()">
                                <v-textarea name="message" rows="1" v-model="message"></v-textarea>
                                <div class="buttons">
                                    <v-btn>+</v-btn>
                                    <v-btn type="submit">Додати</v-btn>
                                </div>
                            </v-form>
                    </v-card>
                </div>
        </v-container>
    </v-layout>
</template>

<script setup>
import {onMounted, computed, ref} from "vue";
import {useChatsStore} from "@/stores/chats";
import {useAuthStore} from "@/stores/auth";
import Loader from "@/components/Loader.vue";
import {date} from "@/mixins/date.js"

const chatsStore = useChatsStore();
const authStore = useAuthStore();
const {humanizeDate, formattedDate} = date()

onMounted(() => {
    chatsStore.getMessages();
});

const message = ref('')
async function handleSubmit() {
    const data = {
        message: message.value,
        user_id: authStore.user.id
    }
    const config = {
        headers: {
            "content-type": "multipart/form-data",
        },
    };
    await chatsStore.storeMessage(data, config)
    chatsStore.getMessages();
}

const isLoader = computed(() => {
    return !chatsStore.isLoadedChats;
});

</script>

<style scoped>
.date {
    display: flex;
    justify-content: right;
}

.buttons {
    display: flex;
    justify-content: space-between;
}

.message {
    bottom: 180px;
}

.test {
    bottom: 75px;
    width: 100%;
    padding-right: 30px;
}

.position {
    position: relative;
}
</style>
