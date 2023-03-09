<template>
    <v-layout column>
        <v-container fluid style="padding-bottom: 160px">
            <div v-if="isLoader">
                <Loader/>
            </div>
            <div v-else>
                <v-col position="fixed" class="title-chat">Чат</v-col>


                <v-card
                    v-for="message in chatsStore.allMessages"
                    :key="message.id"
                    rounded="3"
                    class="mb-3 message"
                    :id="message.id"
                >
                    <div class="message-name">
                        {{ message.user.name }} {{ message.user.surname }}
                    </div>
                    <div>
                        {{ message.message }}
                    </div>                    <div>
                        {{ message.id }}
                    </div>
                    <div class="message-date">
                        {{ humanizeDate(message.created_at) }}
                    </div>

                </v-card>

                <v-card position="fixed" class="btn-chat">
                    <v-card-text>
                        <v-form @submit.prevent="handleSubmit()">
                            <v-row>
                                <v-col cols="10">
                                    <v-textarea v-model="message" name="message" rows="1" auto-grow></v-textarea>
                                    <v-file-input
                                        name="files[]"
                                        type="file"
                                        label="Додати фото або файл"
                                        prepend-icon="mdi:mdi-camera"
                                        multiple
                                        density="compact"
                                        hint
                                        @change="onFileChange"
                                        class="mr-3"
                                    ></v-file-input>
                                    <v-row class="mb-3" v-if="imagesPreview.length">
                                        <v-col
                                            v-for="image in imagesPreview"
                                            :key="image"
                                            cols="6"
                                            class="relative"
                                        >
                                            <v-img
                                                class="bg-white rounded border"
                                                :aspect-ratio="1"
                                                :src="image.f"
                                            ></v-img>
                                            <v-btn
                                                icon
                                                size="x-small"
                                                class="absolute bg-red-accent-2"
                                                @click="removeImage(image)"
                                            >
                                                <v-icon
                                                    class="text-white bg-red-accent-2"
                                                    icon="mdi:mdi-close"
                                                ></v-icon
                                                >
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                </v-col>


                                <v-col cols="2">
                                    <v-btn
                                        class="ma-2"
                                        color="green"
                                        icon="mdi:mdi-send"
                                        type="submit"
                                    ></v-btn>
                                </v-col>
                            </v-row>


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
    console.log(111)
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
    await chatsStore.getMessages();
    message.value = ''
    setTimeout(() => {
        scrollToBottom();
    }, 5000);
}

function scrollToBottom() {
    window.scrollTo(0, document.body.scrollHeight);
}

const isLoader = computed(() => {
    return !chatsStore.isLoadedChats;
});

const showPreview = ref(false);
const imagesPreview = ref([]);
const storedImages = ref([]);
function onFileChange(e) {
    let files = Array.from(e.target.files);
    if (!files) {
        return;
    }
    files.forEach((f) => {
        let reader = new FileReader();
        reader.onload = (e) => {
            showPreview.value = true;
            imagesPreview.value.push({ f: e.target.result, file: f });
            storedImages.value.push(f);
        };

        reader.readAsDataURL(f);
    });
}

function removeImage(image) {
    imagesPreview.value = imagesPreview.value.filter((i) => i.f !== image.f);
    storedImages.value = storedImages.value.filter(
        (i) => i.name !== image.file.name
    );
}

</script>

<style scoped>
.message-date {
    display: flex;
    justify-content: right;
    font-size: 12px;
}

.message-name {
    font-size: 13px;
}

.buttons {
    display: flex;

}

.message {
    padding: 10px;
    width: 100%;
    height: 100%;
}


.title-chat {
    position: fixed;
    top: 62px;
    left: 0px;
    width: 100%;
    height: 50px;
    z-index: 1000;
    display: flex;
    justify-content: center;
    background-color: white;
}

.btn-chat {
    bottom: 70px;
    left: 0px;
    width: 100%;
}


.position {
    position: relative;
}
</style>
