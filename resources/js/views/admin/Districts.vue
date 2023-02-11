<template>
    <v-layout column>
        <v-container fluid>
            <v-card>
                <v-card-title>Райони Міста</v-card-title>
<!--                Add Districts-->
                <add-district
                    @get-districts-pagination="getDistrictsPagination"
                />
<!--                End Add Districts-->
                <v-card-title> Всі райони</v-card-title>
                <v-card-text v-if="isLoader">
                    <v-col cols="12" class="d-flex justify-center">
                        <v-progress-circular
                            :size="50"
                            indeterminate
                            color="green"
                        ></v-progress-circular>
                    </v-col>
                </v-card-text>
<!--                Cards Districts-->
                <div v-else>
                    <v-card-text>
                        <v-expansion-panels>
                            <v-expansion-panel
                                v-for="(district, i) in paginationDistricts"
                                :key="i"
                                elevation="1"
                            >
                                <v-expansion-panel-title>
                                    <v-row>
                                        <v-col
                                            cols="12"
                                            class="d-flex align-self-center"
                                        >
                                            <span>{{ district.title }}</span>
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-title>
                                <v-expansion-panel-text>
                                    <v-row>
<!--                                        <v-col cols="12" class="">-->
<!--                                            <v-chip-->
<!--                                                v-for="permission in role.permissions"-->
<!--                                                :key="permission.id"-->
<!--                                                density="comfortable"-->
<!--                                            >-->
<!--                                                {{ permission.name }}</v-chip-->
<!--                                            >-->
<!--                                        </v-col>-->
                                        <v-col
                                            cols="12"
                                            class="d-flex justify-center"
                                        >
                                            <v-btn
                                                icon
                                                color="green"
                                                class="mr-5"
                                                width="var(--v-button-width)"
                                                height="var(--v-button-height)"
                                                @click="
                                                    isEditDistrictModal = true
                                                "
                                            >
                                                <v-icon
                                                >mdi:mdi-pencil</v-icon
                                                >
                                            </v-btn>

                                            <v-dialog
                                                v-model="
                                                    isEditDistrictModal
                                                "
                                                max-width="600px"
                                            >
                                                <v-form
                                                    ref="form"
                                                    v-model="valid"
                                                    @submit.prevent="
                                                        handleSubmit(district)
                                                    "
                                                >
                                                    <v-card max-width="600px">
                                                        <v-card-title>
                                                            <span
                                                                class="text-h5"
                                                            >
                                                                Редагувати
                                                                район</span
                                                            >
                                                        </v-card-title>
<!--                                                        <v-card-text>-->
<!--                                                            <v-row>-->
<!--                                                                <v-col-->
<!--                                                                    cols="12"-->
<!--                                                                >-->
<!--                                                                    <v-select-->
<!--                                                                        v-model="-->
<!--                                                                            role.permissions-->
<!--                                                                        "-->
<!--                                                                        :items="-->
<!--                                                                            allPermissions-->
<!--                                                                        "-->
<!--                                                                        item-title="name"-->
<!--                                                                        item-value="id"-->
<!--                                                                        label="Дозволи"-->
<!--                                                                        :rules="-->
<!--                                                                            permissionsRules-->
<!--                                                                        "-->
<!--                                                                        multiple-->
<!--                                                                        chips-->
<!--                                                                    >-->
<!--                                                                    </v-select>-->
<!--                                                                </v-col>-->
<!--                                                            </v-row>-->
<!--                                                        </v-card-text>-->
                                                        <v-card-text>
                                                            <v-col
                                                                cols="12"
                                                                class="d-flex justify-center"
                                                            >
                                                                <v-progress-circular
                                                                    v-if="
                                                                        isEditDistrictModalLoader
                                                                    "
                                                                    indeterminate
                                                                    color="green"
                                                                    class="justify-center"
                                                                ></v-progress-circular>
                                                            </v-col>
                                                        </v-card-text>

                                                        <v-card-actions>
                                                            <v-spacer></v-spacer>
                                                            <v-btn
                                                                color="green-darken-1"
                                                                text
                                                                @click="
                                                                    isEditDistrictModal = false;
                                                                    isEditDistrictModalLoader = false;
                                                                "
                                                            >
                                                                Відмінити
                                                            </v-btn>
                                                            <v-btn
                                                                color="green-darken-1"
                                                                text
                                                                :disabled="
                                                                    isEditDistrictModalLoader
                                                                "
                                                                type="submit"
                                                            >
                                                                Редагувати
                                                            </v-btn>
                                                        </v-card-actions>
                                                    </v-card>
                                                </v-form>
                                            </v-dialog>

                                            <v-dialog
                                                v-model="
                                                    isEditDistrictConfirmationModal
                                                "
                                                max-width="600px"
                                            >
                                                <v-card>
                                                    <v-card-text>
                                                        <span class="text-h5"
                                                        >Ви успішно
                                                            відредагували
                                                            район</span
                                                        >
                                                    </v-card-text>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn
                                                            color="green-darken-1"
                                                            text
                                                            @click="closeEditDistrictConfirmationModal"
                                                        >
                                                            Закрити
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                            <!--End Edit District-->
                                            <!--Delete District-->
                                            <v-btn
                                                icon
                                                color="red"
                                                width="var(--v-button-width)"
                                                height="var(--v-button-height)"
                                                @click="
                                                    isDeleteDistrictModal = true
                                                "
                                            >
                                                <v-icon>mdi:mdi-delete</v-icon>
                                            </v-btn>

                                            <v-dialog
                                                v-model="isDeleteDistrictModal"
                                                max-width="600px"
                                            >
                                                <v-card>
                                                    <v-card-text>
                                                        <span class="text-h5"
                                                        >Бажаєте видалити
                                                            {{
                                                                district.title
                                                            }}?</span
                                                        >
                                                    </v-card-text>
                                                    <v-card-text>
                                                        <v-col
                                                            cols="12"
                                                            class="d-flex justify-center"
                                                        >
                                                            <v-progress-circular
                                                                v-if="
                                                                    isDeleteDistrictModalLoader
                                                                "
                                                                indeterminate
                                                                color="green"
                                                                class="justify-center"
                                                            ></v-progress-circular>
                                                        </v-col>
                                                    </v-card-text>

                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn
                                                            color="green-darken-1"
                                                            text
                                                            @click="
                                                                isDeleteDistrictModal = false;
                                                                isDeleteDistrictModalLoader = false;
                                                            "
                                                        >
                                                            Відмінити
                                                        </v-btn>
                                                        <v-btn
                                                            color="green-darken-1"
                                                            text
                                                            :disabled="
                                                                isDeleteDistrictModalLoader
                                                            "
                                                            @click="
                                                                deleteDistrict(district)
                                                            "
                                                        >
                                                            Видалити
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>

                                            <v-dialog
                                                v-model="
                                                    isDeleteDistrictConfirmationModal
                                                "
                                                max-width="600px"
                                            >
                                                <v-card>
                                                    <v-card-text>
                                                        <span class="text-h5"
                                                        >Ви успішно видалили
                                                            {{
                                                                district.title
                                                            }}</span
                                                        >
                                                    </v-card-text>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn
                                                            color="green-darken-1"
                                                            text
                                                            @click="closeDeleteDistrictConfirmationModal"
                                                        >
                                                            Закрити
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                            <!--End Delete District-->
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-text>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-card-text>
                    <!--End Cards Districts-->
                    <!--Pagination Districts-->
                    <v-card-text>
                        <v-col cols="12">
                            <v-pagination
                                v-model="paginationCurrentPage"
                                :length="paginationLastPage"
                                :total-visible="5"
                                @update:modelValue="onPageChange"
                            ></v-pagination>
                        </v-col>
                    </v-card-text>
                    <!--End Pagination Districts-->
                </div>
            </v-card>
        </v-container>
    </v-layout>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { getAllDistricts } from "@/mixins/getAllDistricts";
import { removeAttribute } from "@/mixins/removeAttribute";
import AddDistrict from "@/components/admin/districts/AddDistrict.vue";

onMounted(() => {
    getDistrictsPagination();
});

const isLoader = ref(false);
const { allDistricts } = getAllDistricts();

// Load Districts ========================================
const paginationCurrentPage = ref(null);
const paginationLastPage = ref(null);
const paginationDistricts = ref(null);

function getDistrictsPagination() {
    isLoader.value = true;
    let params = {};
    axios
        .get(
            "/api/admin/get-districts-pagination?page=" + paginationCurrentPage.value,
            {
                params,
            }
        )
        .then(({ data }) => {
            paginationDistricts.value = data.districts.data;
            paginationCurrentPage.value = data.districts.current_page;
            paginationLastPage.value = data.districts.last_page;
            isLoader.value = false;
            console.log(paginationDistricts.value)
        })
        .catch((error) => {
            console.log('error', error);
        });
}

// Edit District ===============================================
const isEditDistrictModal = ref(false);
const isEditDistrictConfirmationModal = ref(false);
const isEditDistrictModalLoader = ref(false);
const permissionsRules = ref([
    (v) => typeof v[0] !== "object" || "Ви не змінили дозволи ролей",
    (v) => v.length !== 0 || "Виберіть хоча б один дозвіл",
]);
const valid = ref(true);
const form = ref(null);
function handleSubmit(district) {
    form.value[0].validate();
    if (!valid.value) {
        return;
    }
    editDistrict(district);
}

function editDistrict(district) {
    isEditDistrictModalLoader.value = true;
    axios
        .post(`/api/admin/edit-district`, {
            id: district.id,
            title: district.title,
            address: district.address,
            order: district.order,
        })
        .then(({ data }) => {
            isEditDistrictModal.value = false;
            isEditDistrictModalLoader.value = false;
            isEditDistrictConfirmationModal.value = true;
        })
        .catch((error) => {
            console.log('error', error);
        })
        .finally(() => {
            isEditDistrictModalLoader.value = false;
        });
}

function closeEditDistrictConfirmationModal() {
    isEditDistrictConfirmationModal.value = false;
    getDistrictsPagination();
    removeAttribute()
}

// Delete District ===============================================
const isDeleteDistrictModal = ref(false);
const isDeleteDistrictConfirmationModal = ref(false);
const isDeleteDistrictModalLoader = ref(false);

function deleteDistrict(district) {
    isDeleteDistrictModalLoader.value = true;
    axios
        .delete(`/api/admin/delete-district/${district.id}`)
        .then(({ data }) => {
            isDeleteDistrictModal.value = false;
            isDeleteDistrictModalLoader.value = false;
            isDeleteDistrictConfirmationModal.value = true;
        })
        .catch((error) => {
            console.log('error', error);
        })
        .finally(() => {
            isDeleteDistrictModalLoader.value = false;
        });
}

function closeDeleteDistrictConfirmationModal() {
    isDeleteDistrictConfirmationModal.value = false;
    getDistrictsPagination();
    removeAttribute()

}

// Pagination ======================================
function onPageChange() {
    getDistrictsPagination();
}

</script>

<style scoped>

</style>
