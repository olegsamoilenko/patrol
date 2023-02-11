<template>
    <v-layout column>
        <v-container fluid>
            <v-card>
                <v-card-title>Додати подію</v-card-title>
                <v-card-text>
                    <v-form
                        ref="form"
                        v-model="valid"
                        @submit.prevent="handleSubmit()"
                    >
                        <v-select
                            v-model="district"
                            name="district"
                            :items="allDistricts"
                            item-title="title"
                            item-value="id"
                            :rules="[(v) => !!v || 'Виберіть патруль']"
                            label="Район"
                        >
                        </v-select>

                        <!--                        TODO: Добавить автоматом определение адреса по координатам-->
                        <v-text-field
                            v-model="address"
                            :rules="[(v) => !!v || 'Введіть адресу']"
                            label="Адреса"
                            required
                        ></v-text-field>

                        <v-text-field
                            v-model="name"
                            label="ПІБ"
                            required
                        ></v-text-field>

                        <v-row>
                            <v-col cols="6">
                                <v-select
                                    v-model="documentType"
                                    :items="documentTypeMap"
                                    label="Документ"
                                >
                                </v-select>
                                <v-text-field
                                    v-if="documentType === 'Інше'"
                                    v-model="documentTypeOther"
                                    label="Назва документу"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field
                                    v-model="document"
                                    label="Серія та номер"
                                ></v-text-field>
                            </v-col>
                        </v-row>

                        <v-expansion-panels class="mb-5">
                            <v-expansion-panel bg-color="#F6F6F6" elevation="1">
                                <v-expansion-panel-title
                                    >Додати Авто</v-expansion-panel-title
                                >
                                <v-expansion-panel-text>
                                    <v-text-field
                                        v-model="number"
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
                                        v-model="carType"
                                        :rules="[(v) => !!v || 'Виберіть тип']"
                                        :items="carTypeMap"
                                        label="Тип Автомобіля"
                                    >
                                    </v-select>
                                    <v-select
                                        v-model="brand"
                                        :rules="[
                                            (v) => !!v || 'Виберіть марку',
                                        ]"
                                        :items="brandMap"
                                        label="Марка Автомобіля"
                                    >
                                    </v-select>

                                    <v-text-field
                                        v-model="model"
                                        label="Модель Автомобіля"
                                    ></v-text-field>
                                    <v-label class="mb-5">Колір</v-label>
                                    <v-color-picker
                                        v-model="color"
                                        name="color"
                                        hide-inputs
                                        :rules="[
                                            (v) => !!v || 'Виберіть марку',
                                        ]"
                                    ></v-color-picker>
                                </v-expansion-panel-text>
                            </v-expansion-panel>
                        </v-expansion-panels>

                        <!--                        <input type="file" name="file" @change="onFileChange">-->

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
                                    ><v-icon
                                        class="text-white bg-red-accent-2"
                                        icon="mdi:mdi-close"
                                    ></v-icon
                                ></v-btn>
                            </v-col>
                        </v-row>

                        <v-textarea
                            v-model="comment"
                            name="comment"
                            label="Коментар"
                            :rules="[(v) => !!v || 'Коментар обов`язково']"
                        />

                        <v-btn type="submit" color="green">Додати</v-btn>

                        <v-dialog
                            v-model="isAddIncidentModal"
                            max-hight="600px"
                            scrollable
                        >
                            <v-card>
                                <v-card max-width="600px">
                                    <v-card-title>
                                        <span class="text-h5"
                                            >Ви успішно додали подію</span
                                        >
                                    </v-card-title>
                                    <v-card-item>
                                        <v-btn
                                            color="green-darken-1"
                                            text
                                            @click="isAddIncidentModal = false, removeAttribute()"
                                            block
                                            class="mb-3"
                                        >
                                            Додати Ще Подію
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
                                        <v-btn
                                            color="green-darken-1"
                                            text
                                            @click="router.push({ name: 'home' }), removeAttribute()"
                                            block
                                            class="mb-3"
                                        >
                                            На Головну
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
import { ref, onMounted  } from "vue";
import { carTypeMap } from "@/utils/maps/carTypeMap";
import { documentTypeMap } from "@/utils/maps/documentTypeMap";
import { brandMap } from "@/utils/maps/brandMap";
import { useRouter } from 'vue-router'
import { removeAttribute } from "@/mixins/removeAttribute";
import { getAllDistricts } from "@/mixins/getAllDistricts";

onMounted(() => {
    getAllDistricts();
})

const router = useRouter()

const district = ref(null);
const address = ref(null);
const name = ref(null);
const documentType = ref(null);
const documentTypeOther = ref(null);
const document = ref(null);
const comment = ref(null);

const number = ref(null);
const carType = ref(null);
const brand = ref(null);
const model = ref(null);
const color = ref(null);

const valid = ref(true);
const form = ref(null);

const { allDistricts } = getAllDistricts();

function handleSubmit(data) {
    form.value.validate();
    if (!valid.value) {
        return;
    }
    addIncident();
}

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

const isAddIncidentModal = ref(false);
async function addIncident() {
    let data = {
        district_id: district.value,
        address: address.value,
        name: name.value,
        document_type: documentType.value,
        document_type_other: documentTypeOther.value,
        document: document.value,
        car_number: number.value ? number.value.toUpperCase() : "",
        car_type: carType.value,
        car_brand: brand.value,
        car_model: model.value,
        car_color: color.value,
        comment: comment.value,
        files: storedImages.value,
    };

    const config = {
        headers: {
            "content-type": "multipart/form-data",
        },
    };

    await axios
        .post("/api/store-incident", data, config)
        .then((data) => {
            isAddIncidentModal.value = true;
            district.value = null;
            address.value = null;
            name.value = null;
            documentType.value = null;
            documentTypeOther.value = null;
            document.value = null;
            number.value = null;
            carType.value = null;
            brand.value = null;
            model.value = null;
            color.value = null;
            comment.value = null;
            storedImages.value = [];
            imagesPreview.value = [];
        })
        .catch((error) => {
            console.log(`error`, error);
        })
        .finally(() => {
            // processing.value = false;
        });
}
</script>

<style scoped>
.relative {
    position: relative;
}
.absolute {
    position: absolute;
    top: 20px;
    right: 20px;
}
/*.v-col {*/
/*    padding: 0;*/
/*}*/
</style>
