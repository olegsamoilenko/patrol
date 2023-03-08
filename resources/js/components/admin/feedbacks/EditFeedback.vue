<template>
    <v-btn
        v-if="authStore.user.roles.find((r) => r.name === 'Супер Адміністратор')"
        icon
        color="green"
        class="mr-5"
        width="var(--v-button-width)"
        height="var(--v-button-height)"
        @click="edit"
    >
        <v-icon>mdi:mdi-pencil</v-icon>
    </v-btn>

    <v-dialog
        v-model="isEditObjectModal"
        max-width="600px"
        scrollable
    >
        <v-card max-height="100vh">
            <v-form
                ref="form"
                v-model="valid"
                lazy-validation
                @submit.prevent="handleSubmit()"
            >
                <v-card max-width="600px">
                    <v-card-title>
                        <span class="text-h5">Редагувати подію</span>
                    </v-card-title>
                    <v-col>
                        <v-text-field
                            v-model="feedback.topic"
                            :rules="[(v) => !!v || 'Тема обов`язкова',
                                    (v) => /^[а-яА-Яа-щА-ЩЬьЮюЯяЇїІіЄєҐґ0-9]+$/.test(v) ||
                                                'Введіть Тему українською'
                                    ]"
                            label="Тема"
                            required
                        ></v-text-field>
                        <v-textarea
                            v-model="feedback.text"
                            name="comment"
                            label="Текст"
                            :rules="[(v) => !!v || 'Коментар обов`язково',
                                    (v) => /^[а-яА-Яа-щА-ЩЬьЮюЯяЇїІіЄєҐґ0-9]+$/.test(v) ||
                                                'Введіть Текст українською'
                                    ]"
                        />
                        <v-select
                            v-model="feedback.status"
                            :items="feedbackStatusMap"
                            placeholder="Статус"
                        >
                        </v-select>
                    </v-col>


                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="green-darken-1"
                            text
                            @click="
                                isEditObjectModal = false;
                                isEditObjectModalLoader = false;
                            "
                        >
                            Відмінити
                        </v-btn>
                        <v-btn
                            color="green-darken-1"
                            text
                            :disabled="isEditObjectModalLoader"
                            type="submit"
                        >
                            Редагувати
                        </v-btn>
                    </v-card-actions>

                </v-card>


            </v-form>
        </v-card>
    </v-dialog>
    <v-dialog
        v-model="isEditObjectConfirmModal"
        max-width="600px"
    >
        <v-card>
            <v-card-text>
                <span class="text-h5">
                    Ви успішно відредагували подію
                </span>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="green-darken-1"
                    text
                    @click="closeEditObjectConfirmModal(); $emit('getFeedbacks'); removeAttribute()"
                >
                    Закрити
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
import {ref, onMounted, defineProps, defineEmits} from "vue";
import {useAuthStore} from "@/stores/auth";
import {editObject} from "@/mixins/editObject";
import {feedbackStatusMap} from "@/utils/maps/feedbackStatusMap";
import {removeAttribute} from "@/mixins/removeAttribute";

const props = defineProps({
    'object': Object,
})

defineEmits(['getFeedbacks'])

onMounted(() => {
    feedback.value = props.object
})

const authStore = useAuthStore();
const feedback = ref(null);

const {
    isEditObjectModal,
    isEditObjectConfirmModal,
    isEditObjectModalLoader,
    editObj,
    closeEditObjectConfirmModal
} = editObject();

function edit() {
    isEditObjectModal.value = true
}

const form = ref(null);
const valid = ref(true);

// TODO При первом нажатии почему-то в valid.value null, а должно быть true
// TODO Переделать валидацию
function handleSubmit() {
    form.value.validate();
    if (!valid.value) {
        console.log('return')
        return;
    }
    prepareFeedback();
}

function prepareFeedback() {
    const data = {
        id: feedback.value.id,
        topic: feedback.value.topic,
        text: feedback.value.text,
        status: feedback.value.status,
    }
    const url = `/api/admin/edit-feedback/${feedback.value.id}`

    editObj(url, data);
}
</script>

<style scoped>

</style>
