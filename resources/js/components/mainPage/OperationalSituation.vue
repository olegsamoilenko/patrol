<template>
    <v-card class="mb-3">
        <div class="title-summary">
            <v-card-title>Oперативна обстановка</v-card-title>
            <div class="date-summary">
                <p>на {{ formattedDate(summaryStore.summary.updated_at) }}</p>
            </div>
        </div>
        <v-card-text>
            {{ summaryStore.summary.text }}
        </v-card-text>
        <v-card-text>
            <v-btn
                v-if="authStore.user.roles.find((r) => r.name === 'Супер Адміністратор') ||
            authStore.user.roles.find((r) => r.name === 'Аналітик') ||
            authStore.checkPermission('Оперативна обстановка редагувати')"
                icon
                color="green"
                class="mr-5"
                width="var(--v-button-width)"
                height="var(--v-button-height)"
                @click="isEditSummaryModal = true"
            >
                <v-icon>mdi:mdi-pencil</v-icon>
            </v-btn>
            <v-dialog
                v-model="isEditSummaryModal"
                max-width="600px"
                scrollable
            >
                <v-card max-height="100vh">
                    <v-form
                        ref="form"
                        v-model="valid"
                        lazy-validation
                        @submit.prevent="handleSubmit(summaryStore.summary)"
                    >
                        <v-card
                            max-width="600px"
                        >
                            <v-card-title>
                                <span class="text-h5">Редагувати оперативну обстановку</span>
                            </v-card-title>
                            <v-col>
                                <v-textarea
                                    v-model="summaryStore.summary.text"
                                    :rules="[(v) => !!v || 'Опишіть оперативну обстановку',
                                    (v) =>
                                        /^[а-яА-Яа-щА-ЩЬьЮюЯяЇїІіЄєҐґ0-9! ]+$/.test(
                                            v
                                        ) ||
                                        'Опишіть оперативну обстановку українськими літерами',
                                        ]"
                                    label="Адреса"
                                    required
                                ></v-textarea>

                                <v-card-text>
                                    <v-col
                                        cols="12"
                                        class="d-flex justify-center"
                                    >
                                        <v-progress-circular
                                            v-if="isEditSummaryModalLoader"
                                            indeterminate
                                            color="green"
                                            class="justify-center"
                                        ></v-progress-circular>
                                    </v-col>
                                </v-card-text>
                            </v-col>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="green-darken-1"
                                    text
                                    @click="
                                isEditSummaryModal = false;
                                isEditSummaryModalLoader = false;
                            "
                                >
                                    Відмінити
                                </v-btn>
                                <v-btn
                                    color="green-darken-1"
                                    text
                                    :disabled="isEditSummaryModalLoader"
                                    type="submit"
                                >
                                    Редагувати
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-form>
                </v-card>
            </v-dialog>
        </v-card-text>
    </v-card>
</template>

<script setup>
import {ref} from 'vue'
import {useAuthStore} from "@/stores/auth";
import {useSummaryStore} from "@/stores/summary";
import dayjs from 'dayjs/esm/index.js';
import uk from '../../locale/dayjs/uk.js';
import relativeTime from 'dayjs/esm/plugin/relativeTime/index.js';

dayjs.extend(relativeTime);

const authStore = useAuthStore();
const summaryStore = useSummaryStore();

function formattedDate(date) {
    if (!date) {
        return null;
    }
    return dayjs(date).locale('uk', uk).format('DD MMMM');
}

const form = ref(null);
const valid = ref(true);

// TODO При первом нажатии почему-то в valid.value null, а должно быть true
// TODO Переделать валидацию
function handleSubmit(summary) {
    form.value.validate();
    if (!valid.value) {
        return;
    }
    editSummary(summary);
}

const isEditSummaryModal = ref(false)
const isEditSummaryModalLoader = ref(false)

// TODO Добавить редактор
function editSummary(summary) {
    isEditSummaryModalLoader.value = true
    axios
        .post("/api/edit-summary", summary)
        .then(({data}) => {
            isEditSummaryModal.value = false
            isEditSummaryModalLoader.value = false
            summaryStore.getSummary()
        })
        .catch((error) => {
            console.log('error', error);
        })
        .finally(() => {
            isEditSummaryModalLoader.value = false
        });
}
</script>

<style scoped>
.title-summary {
    display: flex;
    justify-content: space-between;
}

.date-summary {
    text-align: right;
    display: flex;
    align-items: center;
    padding-right: 16px;
    font-size: 12px;
}
</style>
