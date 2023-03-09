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
                        <v-card-title>Нове повідомлення</v-card-title>
                        <v-card-text>
                            <v-form @submit.prevent="handleSubmit()">
                                <v-textarea name="message" rows="1" v-model="message"></v-textarea>
                                <div class="buttons">
<!--                                    <v-file-input-->
<!--                                        name="files[]"-->
<!--                                        type="file"-->
<!--                                        label="Додати фото або файл"-->
<!--                                        prepend-icon="mdi:mdi-camera"-->
<!--                                        multiple-->
<!--                                        density="compact"-->
<!--                                        hint-->
<!--                                        @change="onFileChange"-->
<!--                                        class="mr-3"-->
<!--                                    ></v-file-input>-->
                                    <v-btn type="submit">Відправити</v-btn>
<!--                                    <v-row class="mb-3">-->
<!--                                        <v-col-->
<!--                                            v-for="image in imagesPreview"-->
<!--                                            :key="image"-->
<!--                                            cols="6"-->
<!--                                            class="relative"-->
<!--                                        >-->
<!--                                            <v-img-->
<!--                                                class="bg-white rounded border"-->
<!--                                                :aspect-ratio="1"-->
<!--                                                :src="image.f"-->
<!--                                            ></v-img>-->
<!--                                            <v-btn-->
<!--                                                icon-->
<!--                                                size="x-small"-->
<!--                                                class="absolute bg-red-accent-2"-->
<!--                                                @click="removeImage(image)"-->
<!--                                            ><v-icon-->
<!--                                                class="text-white bg-red-accent-2"-->
<!--                                                icon="mdi:mdi-close"-->
<!--                                            ></v-icon-->
<!--                                            ></v-btn>-->
<!--                                        </v-col>-->
<!--                                    </v-row>-->
                                </div>
                            </v-form>
                        </v-card-text>

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
