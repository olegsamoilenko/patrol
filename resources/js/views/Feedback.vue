<template>
    <v-layout column>
        <v-container fluid>
            <v-card class="mb-3">
                <v-card-title>Зворотній зв’язок</v-card-title>
                <v-card-text>
                    <v-col>
                        <div>
                            Якщо щось не зрозуміло.
                        </div>
                        <div>
                            Тел: 095 215 53 56 Олег
                        </div>

                    </v-col>
                    <v-col>
                        Якщо є якісь пропозиції стосовно функціоналу, або ви знайшли помилку в роботі додатку.
                    </v-col>

                </v-card-text>
                <v-card-text>
                    <v-form
                        ref="form"
                        v-model="valid"
                        @submit.prevent="handleSubmit()"
                    >
                        <v-text-field
                            v-model="user"
                            label="Від кого"
                            required
                            disabled
                        ></v-text-field>
                        <v-text-field
                            v-model="topic"
                            :rules="[(v) => !!v || 'Тема обов`язкова',
                                    (v) => /^[а-яА-Яа-щА-ЩЬьЮюЯяЇїІіЄєҐґ0-9]+$/.test(v) ||
                                                'Введіть Тему українською'
                                    ]"
                            label="Тема"
                            required
                        ></v-text-field>
                        <v-textarea
                            v-model="text"
                            name="comment"
                            label="Текст"
                            :rules="[(v) => !!v || 'Коментар обов`язково',
                                    (v) => /^[а-яА-Яа-щА-ЩЬьЮюЯяЇїІіЄєҐґ0-9]+$/.test(v) ||
                                                'Введіть Текст українською'
                                    ]"
                        />

                        <v-btn
                            color="green-darken-1"
                            :loading="loading"
                            :disabled="loading"
                            type="submit"
                        >
                            Відправити
                        </v-btn>

                        <v-dialog
                            v-model="isAddFeedbackModal"
                            max-hight="600px"
                            scrollable
                        >
                            <v-card>
                                <v-card max-width="600px">
                                    <v-card-title>
                                        <span class="text-h5"
                                        >Повідомлення відравлене</span
                                        >
                                    </v-card-title>
                                    <v-card-item>
                                        <v-btn
                                            color="green-darken-1"
                                            text
                                            @click="router.push({ name: 'home' }), removeAttribute()"
                                            block
                                            class="mb-3"
                                        >
                                            На Головну
                                        </v-btn>
                                        <v-btn
                                            color="green-darken-1"
                                            text
                                            @click="router.push({ name: 'incidents' }), removeAttribute()"
                                            block
                                            class="mb-3"
                                        >
                                            Переглянути Всі Події
                                        </v-btn>
                                    </v-card-item>
                                </v-card>
                            </v-card>
                        </v-dialog>
                    </v-form>
                </v-card-text>
            </v-card>

        </v-container>
    </v-layout>
</template>

<script setup>
import {computed, ref} from "vue";
import {useAuthStore} from "@/stores/auth";
import { useRouter } from 'vue-router';
import { removeAttribute } from "@/mixins/removeAttribute";

const router = useRouter()

const authStore = useAuthStore();

const user = computed(() => authStore.user.name + ' ' + authStore.user.surname);

const topic = ref('');
const text = ref('');

const loading = ref(false);

const valid = ref(true);
const form = ref(null);
function handleSubmit(data) {
    form.value.validate();
    if (!valid.value) {
        return;
    }
    addFeedback();
}

const isAddFeedbackModal = ref(false);
async function addFeedback() {
    loading.value = true;
    let data = {
        user_id: authStore.user.id,
        topic: topic.value,
        text: text.value,
        status: 'Новий',
    };

    await axios
        .post("/api/store-feedback", data)
        .then((data) => {
            isAddFeedbackModal.value = true;
            loading.value = false;
        })
        .catch((error) => {
            console.log(`error`, error);
        })
        .finally(() => {
            // processing.value = false;
            // TODO: Видно валидацию после отправки и обнуления полей
            topic.value = '';
            text.value = '';
        });
}


</script>

<style scoped>

</style>
