<template>
    <!--Edit Incident--------------------------------------------->
    <v-btn
        v-if="authStore.user.roles.find((r) => r.name === 'Супер Адміністратор') ||
            authStore.user.roles.find((r) => r.name === 'Аналітик') ||
            authStore.checkPermission('Інцидент редагувати') ||
            authStore.user.id === incident.user_id
        "
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
                @submit.prevent="handleSubmit(incident)"
            >
                <v-card
                    max-width="600px"
                >
                    <v-card-title>
                        <span class="text-h5">Редагувати подію</span>
                    </v-card-title>
                    <v-col>
                        <v-select
                            v-model="incident.district_id"
                            name="district"
                            :items="districtsStore.allDistricts"
                            item-title="title"
                            item-value="id"
                            :rules="[(v) => !!v || 'Виберіть район']"
                            label="Район"
                        >
                        </v-select>

                        <!--                        TODO: Добавить автоматом определение адреса по координатам-->
                        <v-textarea
                            v-model="incident.address"
                            :rules="[(v) => !!v || 'Введіть адресу',
                                    (v) =>
                                        /^[а-яА-Яа-щА-ЩЬьЮюЯяЇїІіЄєҐґ0-9., ]+$/.test(
                                            v
                                        ) ||
                                        'Введіть адресу українськими літерами',
                                        ]"
                            label="Адреса"
                            required
                        ></v-textarea>

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
                                    v-if="isEditObjectModalLoader"
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
                    @click="closeEditObjectConfirmModal(); $emit('getIncidents'); removeAttribute()"
                >
                    Закрити
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!--End Edit Incident------------------------------------------------------>
</template>

<script setup>
import {ref, onMounted, defineProps, defineEmits} from "vue";
import {useAuthStore} from "@/stores/auth";
import {useDistrictsStore} from "@/stores/districts";
import {carTypeMap} from "@/utils/maps/carTypeMap";
import {documentTypeMap} from "@/utils/maps/documentTypeMap";
import {brandMap} from "@/utils/maps/brandMap";
import {removeAttribute} from "@/mixins/removeAttribute";
import {editObject} from "@/mixins/editObject";

const props = defineProps({
    'object': Object,
})

defineEmits(['getIncidents'])

onMounted(() => {
    incident.value = props.object
})

const incident = ref({})

const authStore = useAuthStore();
const districtsStore = useDistrictsStore();

const {
    isEditObjectModal,
    isEditObjectConfirmModal,
    isEditObjectModalLoader,
    editObj,
    closeEditObjectConfirmModal
} = editObject();

function edit() {
    isEditObjectModal.value = true
    imagesIncident.value = incident.value.media
}


const form = ref(null);
const valid = ref(true);

// TODO При первом нажатии почему-то в valid.value null, а должно быть true
// TODO Переделать валидацию
function handleSubmit(incident) {
    form.value.validate();
    if (!valid.value) {
        console.log('return')
        return;
    }
    console.log(222)
    prepareIncident(incident);
}

const deleteImagesIncident = ref(null)

function prepareIncident(incident) {
    deleteImagesIncident.value = incident.media.map(JSON.stringify).filter(e => !imagesIncident.value.map(JSON.stringify).includes(e)).map(JSON.parse);

    const data = {
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
        deleteImagesIncident: JSON.stringify(deleteImagesIncident.value.map(i => {
            return i.id
        })),
        files: storeImagesIncident.value,
    };

    const config = {
        headers: {
            "content-type": "multipart/form-data",
        },
    };

    const url = `/api/edit-incident/${incident.id}`

    editObj(url, data, config);

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
.font-bold {
    font-weight: bold;
}
</style>
