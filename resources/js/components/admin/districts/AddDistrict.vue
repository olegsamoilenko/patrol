<template>
    <!--Add District-->
    <v-card-text>
        <v-col cols="12" class="d-flex justify-start">
            <v-btn color="green" @click="isAddDistrictModal = true">
                Додати район
            </v-btn>
            <v-dialog v-model="isAddDistrictModal" max-width="600px">
                <v-form
                    ref="form"
                    v-model="valid"
                    @submit.prevent="handleSubmit()"
                >
                    <v-card max-width="600px">
                        <v-card-title>
                            <span class="text-h5"> Додати район</span>
                        </v-card-title>
                        <v-card-text>
                            <div class="pb-2" v-if="makeChoiceError"><span class="text-red">Введіть Номер Патруля або Район</span>
                            </div>
                            <v-expansion-panels
                                v-model="panel"
                                class="mb-5"
                                :rules="[
                                            (v) => !!v || 'Введіть район'
                                        ]"
                            >
                                <v-expansion-panel bg-color="#F6F6F6" elevation="1" value="ifPatrol">
                                    <v-expansion-panel-title
                                    >Додати Патруль
                                    </v-expansion-panel-title
                                    >
                                    <v-expansion-panel-text>
                                        <div class="" v-if="validationErrorsFromBase"><span class="text-error">{{ validationErrorsFromBase.title[0] }}</span>
                                        </div>
                                        Патруль №:
                                        <v-text-field
                                            v-model="patrolNumber"
                                            :rules="[
                                            (v) => !!v || 'Введіть номер',
                                            (v) =>
                                                /^[0-9]+$/.test(
                                                    v
                                                ) ||
                                                'Введіть номер',
                                        ]"
                                            prepend-inner-icon="mdi:mdi-car"
                                            label="Номер Патруля"
                                            type="number"
                                        >
                                        </v-text-field>

                                        <v-text-field
                                            v-model="newDistrictStreets"
                                            :rules="[
                                                (v) => !!v || 'Введіть вулиці',
                                                (v) =>
                                                    /^[а-яА-Яа-щА-ЩЬьЮюЯяЇїІіЄєҐґ0-9]+$/.test(
                                                        v
                                                    ) ||
                                                    'Введіть назву вулиці українською',
                                        ]"
                                            prepend-inner-icon="mdi:mdi-car"
                                            label="Вулиці"
                                            type="text"
                                        >
                                        </v-text-field>

                                        <v-select
                                            v-model="insertAfterOrder"
                                            :rules="[(v) =>
                                                /^[0-9]+$/.test(
                                                    v
                                                ) ||
                                                'Зробіть вибір']"
                                            :items="allDistricts"
                                            item-title="title"
                                            item-value="order"
                                            label="Вставити після"
                                        >
                                        </v-select>
                                    </v-expansion-panel-text>
                                </v-expansion-panel>
                                <div class="my-3"><span>Або</span></div>
                                <v-expansion-panel bg-color="#F6F6F6" elevation="1" value="ifDistrict">
                                    <v-expansion-panel-title
                                    >Додати Район
                                    </v-expansion-panel-title
                                    >
                                    <v-expansion-panel-text>
                                        <v-text-field
                                            v-model="nameDistrict"
                                            :rules="[
                                            (v) => !!v || 'Введіть район',
                                            (v) =>
                                                /^[а-яА-Яа-щА-ЩЬьЮюЯяЇїІіЄєҐґ0-9]+$/.test(
                                                    v
                                                ) ||
                                                'Введіть назву району українською',
                                        ]"
                                            prepend-inner-icon="mdi:mdi-car"
                                            label="Назва району"
                                            type="text"
                                        >
                                        </v-text-field>
                                        <v-select
                                            v-model="insertAfterOrder"
                                            :rules="[(v) =>
                                                /^[0-9]+$/.test(
                                                    v
                                                ) ||
                                                'Зробіть вибір']"
                                            :items="allDistricts"
                                            item-title="title"
                                            item-value="order"
                                            label="Вставити після"
                                        >
                                        </v-select>
                                    </v-expansion-panel-text>
                                </v-expansion-panel>
                            </v-expansion-panels>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="green-darken-1"
                                text
                                @click="
                                    isAddDistrictModal = false;
                                    isAddDistrictModalLoader = false;
                                "
                            >
                                Відмінити
                            </v-btn>
                            <v-btn
                                color="green-darken-1"
                                text
                                :disabled="isAddDistrictModalLoader"
                                type="submit"
                            >
                                Додати
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </v-dialog>

            <v-dialog v-model="isAddDistrictConfirmationModal" max-width="600px">
                <v-card>
                    <v-card-text>
                        <span class="text-h5">Ви успішно додали район</span>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="green-darken-1"
                            text
                            @click="
                                $emit('getDistrictsPagination');
                                isAddDistrictConfirmationModal = false;
                            "
                        >
                            Закрити
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-col>
    </v-card-text>
    <!--End Add District-->
</template>

<script setup>
// Add Role ===============================================
import {ref, onMounted, watch} from "vue";
import {getAllDistricts} from "@/mixins/getAllDistricts";

onMounted(() => {
    getAllDistricts();
});

defineEmits(["getDistrictsPagination"]);

const {allDistricts} = getAllDistricts();

const panel = ref(null);
watch(panel, async (newValue, oldValue) => {
    if (newValue) {
        makeChoiceError.value = false;
    }
})

const patrolNumber = ref(null);
const nameDistrict = ref("");
const newDistrictTitle = ref("");
const newDistrictStreets = ref(null);
const insertAfterOrder = ref(null);

const isAddDistrictModal = ref(false);
const isAddDistrictConfirmationModal = ref(false);
const isAddDistrictModalLoader = ref(false);
const validationErrorsFromBase = ref(null);
const makeChoiceError = ref(false);

const valid = ref(true);
const form = ref(null);

function handleSubmit() {
    form.value.validate();
    if (!valid.value) {
        return;
    }
    AddDistrict();
}

function AddDistrict() {
    // isAddDistrictModalLoader.value = true;
    if (!panel.value) {
        makeChoiceError.value = true;
    } else if (panel.value === "ifPatrol") {
        newDistrictTitle.value = "Патруль №: " + patrolNumber.value;
        nameDistrict.value = null;
    } else {
        newDistrictTitle.value = nameDistrict.value;
        patrolNumber.value = null;
        newDistrictStreets.value = null;
    }
    let params = {
        title: newDistrictTitle.value,
        streets: newDistrictStreets.value,
        order: Number(insertAfterOrder.value)+1,
    };
    console.log(params)
    axios
        .post(`/api/admin/add-district`, params)
        .then(({data}) => {
            isAddDistrictModal.value = false;
            isAddDistrictModalLoader.value = false;
            isAddDistrictConfirmationModal.value = true;
            newDistrictTitle.value = "";
            newDistrictStreets.value = [];
            patrolNumber.value = null;
            nameDistrict.value = "";
            insertAfterOrder.value = null;
            validationErrorsFromBase.value = null;
            panel.value = null;
        })
        .catch(({response}) => {
            if (response.status === 422) {
                validationErrorsFromBase.value = response.data.errors;
                console.log(validationErrorsFromBase.value)
            } else {
                validationErrorsFromBase.value = {};
                alert(response.data.message);
            }
        })
        .finally(() => {
            isAddDistrictModalLoader.value = false;
        });
}
</script>

<style>
.v-messages {
    flex: 0 1 auto;
}
</style>
