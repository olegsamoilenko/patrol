<template>
    <v-btn
        v-if="authStore.user.roles.find((r) => r.name === 'Супер Адміністратор') ||
            authStore.user.roles.find((r) => r.name === 'Аналітик')
        "
        icon
        color="green"
        class="mr-5"
        width="var(--v-button-width)"
        height="var(--v-button-height)"
        @click="move"
    >
        <v-icon>mdi:mdi-reply</v-icon>
    </v-btn>

    <v-dialog
        v-model="isMoveObjectModal"
        max-width="600px"
        scrollable
    >
        <v-card max-height="100vh">
            <v-form
                ref="form"
                v-model="valid"
                lazy-validation
                @submit.prevent="handleSubmit(incident)"
            >
                <v-card
                    max-width="600px"
                >
                    <v-card-title>
                        <span class="text-h5">Додати на головну</span>
                    </v-card-title>
                    <v-col>
                        <v-text-field
                            v-model="incident.name"
                            label="ПІБ"
                            :rules="[(v) =>
                                        /^[а-яА-Яа-щА-ЩЬьЮюЯяЇїІіЄєҐґ0-9 ]+$/.test(
                                            v
                                        ) ||
                                        'Введіть ПІБ українськими літерами',
                                        ]"
                            required
                        ></v-text-field>


                        <v-col cols="12">
                            <v-select
                                v-model="incident.document_type"
                                :items="documentTypeMap"
                                label="Документ"
                            >
                            </v-select>
                            <v-text-field
                                v-if="incident.document_type === 'Інше'"
                                v-model="incident.document_type_other"
                                label="Назва документу"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                v-model="incident.document"
                                label="Серія та номер"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" v-if="incident.car_number || incident.car_brand">
                            <v-col class="font-bold">
                                Автомобіль
                            </v-col>
                            <v-text-field
                                v-model="incident.car_number"
                                :rules="[
                                    (v) => !!v || 'Введіть номер',
                                    (v) =>
                                        /^[а-яА-Яа-щА-ЩЬьЮюЯяЇїІіЄєҐґ0-9]+$/.test(
                                            v
                                        ) ||
                                        'Введіть номер українськими літерами без пробілів та спецсимволів',
                                ]"
                                prepend-inner-icon="mdi:mdi-car"
                                label="Номер Автомобіля"
                                type="text"
                            >
                            </v-text-field>

                            <v-select
                                v-model="incident.car_type"
                                :rules="[(v) => !!v || 'Виберіть тип']"
                                :items="carTypeMap"
                                label="Тип Автомобіля"
                            >
                            </v-select>
                            <v-select
                                v-model="incident.car_brand"
                                :rules="[
                                            (v) => !!v || 'Виберіть марку',
                                        ]"
                                :items="brandMap"
                                label="Марка Автомобіля"
                            >
                            </v-select>

                            <v-text-field
                                v-model="incident.car_model"
                                label="Модель Автомобіля"
                            ></v-text-field>
                            <v-label class="mb-5">Колір</v-label>
                            <v-color-picker
                                v-model="incident.car_color"
                                name="color"
                                hide-inputs
                                :rules="[
                                            (v) => !!v || 'Виберіть колір',
                                        ]"
                            ></v-color-picker>
                        </v-col>

                        <v-file-input
                            name="files[]"
                            type="file"
                            label="Додати фото"
                            prepend-icon="mdi:mdi-camera"
                            multiple
                            @change="onFileChange"
                        ></v-file-input>

                        <v-row class="mb-3">
                            <v-col
                                v-for="image in imagesIncident"
                                :key="image"
                                cols="6"
                                class="relative"
                            >
                                <v-img
                                    class="bg-white rounded border"
                                    :aspect-ratio="1"
                                    :src="'storage/' + image.id + '/' + image.file_name"
                                ></v-img>
                                <v-btn
                                    icon
                                    size="x-small"
                                    class="absolute bg-red-accent-2"
                                    @click="deleteImageIncident(image)"
                                >
                                    <v-icon
                                        class="text-white bg-red-accent-2"
                                        icon="mdi:mdi-close"
                                    ></v-icon
                                    >
                                </v-btn>
                            </v-col>

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
                                    @click="removeImagePreview(image)"
                                >
                                    <v-icon
                                        class="text-white bg-red-accent-2"
                                        icon="mdi:mdi-close"
                                    ></v-icon
                                    >
                                </v-btn>
                            </v-col>
                        </v-row>

                        <v-card-text class="font-bold">
                                Якщо потрібно, відредагуйте комментар
                        </v-card-text>
                        <v-textarea
                            v-model="incident.comment"
                            name="comment"
                            label="Коментар"
                            :rules="[(v) => !!v || 'Коментар обов`язково',
                                    (v) => /^[а-яА-Яа-щА-ЩЬьЮюЯяЇїІіЄєҐґ0-9., ]+$/.test(
                                            v
                                        ) ||
                                        'Напишіть комментар українськими літерами',
                            ]"
                        />
                        <v-card-text>
                            <v-col
                                cols="12"
                                class="d-flex justify-center"
                            >
                                <v-progress-circular
                                    v-if="isMoveObjectModalLoader"
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
                                isMoveObjectModal = false;
                                isMoveObjectModalLoader = false;
                            "
                        >
                            Відмінити
                        </v-btn>
                        <v-btn
                            color="green-darken-1"
                            text
                            :disabled="isMoveObjectModalLoader"
                            type="submit"
                        >
                            Додати
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-card>
    </v-dialog>

    <v-dialog
        v-model="isMoveObjectConfirmModal"
        max-width="600px"
    >
        <v-card>
            <v-card-text>
                <span class="text-h5">
                    Ви успішно додали інформацію на головну
                </span>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="green-darken-1"
                    text
                    @click="closeMoveObjectConfirmModal(); $emit('moveIncidents'); removeAttribute()"
                >
                    Перейти на головну
                </v-btn>
                <v-btn
                    color="green-darken-1"
                    text
                    @click="closeMoveObjectConfirmModal(); $emit('moveIncidents'); removeAttribute()"
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
import {useDistrictsStore} from "@/stores/districts";
import {carTypeMap} from "@/utils/maps/carTypeMap";
import {documentTypeMap} from "@/utils/maps/documentTypeMap";
import {brandMap} from "@/utils/maps/brandMap";
import {removeAttribute} from "@/mixins/removeAttribute";

const props = defineProps({
    'object': Object,
})

defineEmits(['moveIncidents'])

onMounted(() => {
    incident.value = props.object
})

const incident = ref({})

const authStore = useAuthStore();
const districtsStore = useDistrictsStore();

const isMoveObjectModal = ref(false);
const isMoveObjectConfirmModal = ref(false);
const isMoveObjectModalLoader = ref(false);
const validationErrorsFromBase = ref({});
function move() {
    isMoveObjectModal.value = true
    imagesIncident.value = incident.value.media
}


const form = ref(null);
const valid = ref(true);

// TODO При первом нажатии почему-то в valid.value null, а должно быть true
// TODO Переделать валидацию
function handleSubmit(incident) {
    form.value.validate();
    if (!valid.value) {
        return;
    }
    prepareIncident(incident);
}

const moveImagesIncident = ref(null)

function prepareIncident(incident) {
    moveImagesIncident.value = incident.media.map(JSON.stringify).filter(e => !imagesIncident.value.map(JSON.stringify).includes(e)).map(JSON.parse);

    let params = {
        id: incident.id,
        district_id: incident.district_id,
        address: incident.address,
        name: incident.name,
        document_type: incident.document_type,
        document_type_other: incident.document_type_other,
        document: incident.document,
        car_number: incident.car_number,
        car_type: incident.car_type,
        car_brand: incident.car_brand,
        car_model: incident.car_model,
        car_color: incident.car_color,
        comment: incident.comment,
        user_id: incident.user_id,
        images: JSON.stringify(imagesIncident.value.map(i => {
            return i.id
        })),
        files: storeImagesIncident.value,
    };

    moveIncident(params);

}



function moveIncident(params) {
    isMoveObjectModalLoader.value = true;
    const config = {
        headers: {
            "content-type": "multipart/form-data",
        },
    };
    axios
        .post(`/api/store-main-page-incident/`, params, config)
        .then(({data}) => {
            isMoveObjectModal.value = false;
            isMoveObjectModalLoader.value = false;
            isMoveObjectConfirmModal.value = true;
        })
        .catch(({response}) => {
            if (response.status === 422) {
                validationErrorsFromBase.value = response.data.errors;
            } else {
                validationErrorsFromBase.value = {};
                alert(response.data.message);
            }
        })
        .finally(() => {
            isMoveObjectModalLoader.value = false;
        });
}

function closeMoveObjectConfirmModal() {
    isMoveObjectConfirmModal.value = false;
    incident.value = props.object
}

// File

const showPreview = ref(false);
const imagesIncident = ref([]);
const imagesPreview = ref([]);
const storeImagesIncident = ref([]);


function deleteImageIncident(image) {
    imagesIncident.value = imagesIncident.value.filter(i => i.id !== image.id)
}

function onFileChange(e) {
    let files = Array.from(e.target.files);
    if (!files) {
        return;
    }
    files.forEach((f) => {
        let reader = new FileReader();
        reader.onload = (e) => {
            showPreview.value = true;
            imagesPreview.value.push({f: e.target.result});
            storeImagesIncident.value.push(f);
        };

        reader.readAsDataURL(f);
    });
}

function removeImagePreview(image) {
    imagesPreview.value = imagesPreview.value.filter((i) => i.f !== image.f);
    storeImagesIncident.value = storeImagesIncident.value.filter(
        (i) => i.name !== image.file.name
    );
}
</script>

<style scoped>

</style>
