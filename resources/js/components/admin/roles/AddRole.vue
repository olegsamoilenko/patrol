<template>
    <!--Add Role-->
    <v-card-text>
        <v-col cols="12" class="d-flex justify-start">
            <v-btn color="green" @click="isAddRoleModal = true">
                Додати роль
            </v-btn>
            <v-dialog v-model="isAddRoleModal" max-width="600px">
                <v-form
                    ref="form"
                    v-model="valid"
                    @submit.prevent="handleSubmit()"
                >
                    <v-card max-width="600px">
                        <v-card-title>
                            <span class="text-h5"> Додати роль</span>
                        </v-card-title>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="newRoleName"
                                        :rules="[
                                            (v) =>
                                                !!v ||
                                                'Поле не може бути порожнім',
                                        ]"
                                        label="Назва ролі"
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
                                        v-model="newRolePermissions"
                                        :items="allPermissions"
                                        item-title="name"
                                        item-value="id"
                                        label="Дозволи"
                                        :rules="[(v) => v.length !== 0 || 'Виберіть хоча б один дозвіл']"
                                        multiple
                                        chips
                                    >
                                        <template #details>
                                            <span
                                                class="text-error text-caption"
                                                v-if="
                                                    validationErrorsFromBase.permissions
                                                "
                                                >{{
                                                    validationErrorsFromBase
                                                        .permissions[0]
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
                                    v-if="isAddRoleModalLoader"
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
                                    isAddRoleModal = false;
                                    isAddRoleModalLoader = false;
                                "
                            >
                                Відмінити
                            </v-btn>
                            <v-btn
                                color="green-darken-1"
                                text
                                :disabled="isAddRoleModalLoader"
                                type="submit"
                            >
                                Додати
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </v-dialog>

            <v-dialog v-model="isAddRoleConfirmationModal" max-width="600px">
                <v-card>
                    <v-card-text>
                        <span class="text-h5">Ви успішно додали роль</span>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="green-darken-1"
                            text
                            @click="
                                $emit('getRoles');
                                isAddRoleConfirmationModal = false;
                            "
                        >
                            Закрити
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-col>
    </v-card-text>
    <!--End Add Role-->
</template>

<script setup>
// Add Role ===============================================
import { ref } from "vue";

defineProps(["allPermissions"]);

defineEmits(["getRoles"]);

const newRoleName = ref("");
const newRolePermissions = ref([]);

const isAddRoleModal = ref(false);
const isAddRoleConfirmationModal = ref(false);
const isAddRoleModalLoader = ref(false);
const validationErrorsFromBase = ref({});

const valid = ref(true);
const form = ref(null);
function handleSubmit() {
    form.value.validate();
    if (!valid.value) {
        return;
    }
    AddRole();
}

function AddRole() {
    isAddRoleModalLoader.value = true;
    let params = {
        name: newRoleName.value,
        permissions: newRolePermissions.value,
    };
    console.log(params);
    axios
        .post(`/admin/add-role`, params)
        .then(({ data }) => {
            console.log("data", data);
            isAddRoleModal.value = false;
            isAddRoleModalLoader.value = false;
            isAddRoleConfirmationModal.value = true;
        })
        .catch(({ response }) => {
            if (response.status === 422) {
                validationErrorsFromBase.value = response.data.errors;
                console.log(validationErrorsFromBase.value);
            } else {
                validationErrorsFromBase.value = {};
                alert(response.data.message);
            }
        })
        .finally(() => {
            isAddRoleModalLoader.value = false;
        });
}
</script>

<style>
.v-messages {
    flex: 0 1 auto;
}
</style>
