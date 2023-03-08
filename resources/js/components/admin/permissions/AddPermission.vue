<template>
    <!--Add Permission-->
    <v-card-text>
        <v-col cols="12" class="d-flex justify-start">
            <v-btn color="green" @click="isAddPermissionModal = true">
                Додати дозвіл
            </v-btn>
            <v-dialog v-model="isAddPermissionModal" max-width="600px">
                <v-form
                    ref="form"
                    v-model="valid"
                    @submit.prevent="handleSubmit()"
                >
                    <v-card max-width="600px">
                        <v-card-title>
                            <span class="text-h5"> Додати дозвіл</span>
                        </v-card-title>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="newPermissionName"
                                        :rules="[
                                            (v) =>
                                                !!v ||
                                                'Поле не може бути порожнім',
                                        ]"
                                        label="Назва дозволу"
                                        required
                                    >
                                        <template #details>
                                            <span
                                                class="text-error text-caption"
                                                v-if="
                                                    validationErrorsFromBase.name
                                                "
                                            >{{
                                                    validationErrorsFromBase
                                                        .name[0]
                                                }}</span
                                            >
                                            <v-spacer />
                                        </template>
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-select
                                        v-model="newPermissionRoles"
                                        :items="allRoles"
                                        item-title="name"
                                        item-value="id"
                                        label="Ролі"
                                        :rules="[(v) => v.length !== 0 || 'Виберіть хоча б одну роль']"
                                        multiple
                                        chips
                                    >
                                        <template #details>
                                            <span
                                                class="text-error text-caption"
                                                v-if="
                                                    validationErrorsFromBase.roles
                                                "
                                            >{{
                                                    validationErrorsFromBase
                                                        .roles[0]
                                                }}</span
                                            >
                                            <v-spacer />
                                        </template>
                                    </v-select>
                                </v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-text>
                            <v-col cols="12" class="d-flex justify-center">
                                <v-progress-circular
                                    v-if="isAddPermissionModalLoader"
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
                                    isAddPermissionModal = false;
                                    isAddPermissionModalLoader = false;
                                "
                            >
                                Відмінити
                            </v-btn>
                            <v-btn
                                color="green-darken-1"
                                text
                                :disabled="isAddPermissionModalLoader"
                                type="submit"
                            >
                                Додати
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </v-dialog>

            <v-dialog v-model="isAddPermissionConfirmationModal" max-width="600px">
                <v-card>
                    <v-card-text>
                        <span class="text-h5">Ви успішно додали дозвіл</span>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="green-darken-1"
                            text
                            @click="
                                $emit('getPermissions');
                                isAddPermissionConfirmationModal = false;
                            "
                        >
                            Закрити
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-col>
    </v-card-text>
    <!--End Add Permission-->
</template>

<script setup>
// Add Permission ===============================================
import { ref } from "vue";

defineProps(["allRoles"]);

defineEmits(["getPermissions"]);

const newPermissionName = ref("");
const newPermissionRoles = ref([]);

const isAddPermissionModal = ref(false);
const isAddPermissionConfirmationModal = ref(false);
const isAddPermissionModalLoader = ref(false);
const validationErrorsFromBase = ref({});

const valid = ref(true);
const form = ref(null);
function handleSubmit() {
    form.value.validate();
    if (!valid.value) {
        return;
    }
    AddPermission();
}

function AddPermission() {
    isAddPermissionModalLoader.value = true;
    let params = {
        name: newPermissionName.value,
        roles: newPermissionRoles.value,
    };
    axios
        .post(`/api/admin/add-permission`, params)
        .then(({ data }) => {
            isAddPermissionModal.value = false;
            isAddPermissionModalLoader.value = false;
            isAddPermissionConfirmationModal.value = true;
            newPermissionName.value = "";
            newPermissionRoles.value = [];
        })
        .catch(({ response }) => {
            if (response.status === 422) {
                validationErrorsFromBase.value = response.data.errors;
            } else {
                validationErrorsFromBase.value = {};
                alert(response.data.message);
            }
        })
        .finally(() => {
            isAddPermissionModalLoader.value = false;
        });
}
</script>

<style>
.v-messages {
    flex: 0 1 auto;
}
</style>

