<template>
    <v-layout column>
        <v-container fluid>
            <v-card>
                <v-card-title> Ролі користувачів</v-card-title>
                <!--Add Role-->
                <add-role
                    :all-permissions="allPermissions"
                    @get-roles="getRoles"
                />
                <!--End Add Role-->
                <v-card-title> Всі ролі</v-card-title>
                <v-card-text v-if="isLoader">
                    <v-col cols="12" class="d-flex justify-center">
                        <v-progress-circular
                            :size="50"
                            indeterminate
                            color="green"
                        ></v-progress-circular>
                    </v-col>
                </v-card-text>
                <!--Cards Roles-->
                <div v-else>
                    <v-card-text>
                        <v-expansion-panels>
                            <v-expansion-panel
                                v-for="(role, i) in paginationRoles"
                                :key="i"
                                elevation="1"
                            >
                                <v-expansion-panel-title>
                                    <v-row>
                                        <v-col
                                            cols="12"
                                            class="d-flex align-self-center"
                                        >
                                            <span>{{ role.name }}</span>
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-title>
                                <v-expansion-panel-text>
                                    <v-row>
                                        <v-col cols="12" class="">
                                            <v-chip
                                                v-for="permission in role.permissions"
                                                :key="permission.id"
                                                density="comfortable"
                                            >
                                                {{ permission.name }}</v-chip
                                            >
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            class="d-flex justify-center"
                                        >
                                            <!--Edit Role Permissions-->
                                            <v-btn
                                                icon
                                                color="green"
                                                class="mr-5"
                                                width="var(--v-button-width)"
                                                height="var(--v-button-height)"
                                                @click="
                                                    isEditRolePermissionsModal = true
                                                "
                                            >
                                                <v-icon
                                                    >mdi:mdi-pencil</v-icon
                                                >
                                            </v-btn>

                                            <v-dialog
                                                v-model="
                                                    isEditRolePermissionsModal
                                                "
                                                max-width="600px"
                                            >
                                                <v-form
                                                    ref="form"
                                                    v-model="valid"
                                                    @submit.prevent="
                                                        handleSubmit(role)
                                                    "
                                                >
                                                    <v-card max-width="600px">
                                                        <v-card-title>
                                                            <span
                                                                class="text-h5"
                                                            >
                                                                Редагувати
                                                                дозволи
                                                                ролей</span
                                                            >
                                                        </v-card-title>
                                                        <v-card-text>
                                                            <v-row>
                                                                <v-col
                                                                    cols="12"
                                                                >
                                                                    <v-select
                                                                        v-model="
                                                                            role.permissions
                                                                        "
                                                                        :items="
                                                                            allPermissions
                                                                        "
                                                                        item-title="name"
                                                                        item-value="id"
                                                                        label="Дозволи"
                                                                        :rules="
                                                                            permissionsRules
                                                                        "
                                                                        multiple
                                                                        chips
                                                                    >
                                                                    </v-select>
                                                                </v-col>
                                                            </v-row>
                                                        </v-card-text>
                                                        <v-card-text>
                                                            <v-col
                                                                cols="12"
                                                                class="d-flex justify-center"
                                                            >
                                                                <v-progress-circular
                                                                    v-if="
                                                                        isEditRolePermissionsModalLoader
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
                                                                    isEditRolePermissionsModal = false;
                                                                    isEditRolePermissionsModalLoader = false;
                                                                "
                                                            >
                                                                Відмінити
                                                            </v-btn>
                                                            <v-btn
                                                                color="green-darken-1"
                                                                text
                                                                :disabled="
                                                                    isEditRolePermissionsModalLoader
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
                                                    isEditRolePermissionsConfirmationModal
                                                "
                                                max-width="600px"
                                            >
                                                <v-card>
                                                    <v-card-text>
                                                        <span class="text-h5"
                                                            >Ви успішно
                                                            відредагували
                                                            {{
                                                                role.name
                                                            }}</span
                                                        >
                                                    </v-card-text>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn
                                                            color="green-darken-1"
                                                            text
                                                            @click="closeEditRolePermissionsConfirmationModal"
                                                        >
                                                            Закрити
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                            <!--End Edit Role Permissions-->
                                            <!--Delete Roles-->
                                            <v-btn
                                                icon
                                                color="red"
                                                width="var(--v-button-width)"
                                                height="var(--v-button-height)"
                                                @click="
                                                    isDeleteRoleModal = true
                                                "
                                            >
                                                <v-icon>mdi:mdi-delete</v-icon>
                                            </v-btn>

                                            <v-dialog
                                                v-model="isDeleteRoleModal"
                                                max-width="600px"
                                            >
                                                <v-card>
                                                    <v-card-text>
                                                        <span class="text-h5"
                                                            >Бажаєте видалити
                                                            {{
                                                                role.name
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
                                                                    isDeleteRoleModalLoader
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
                                                                isDeleteRoleModal = false;
                                                                isDeleteRoleModalLoader = false;
                                                            "
                                                        >
                                                            Відмінити
                                                        </v-btn>
                                                        <v-btn
                                                            color="green-darken-1"
                                                            text
                                                            :disabled="
                                                                isDeleteRoleModalLoader
                                                            "
                                                            @click="
                                                                deleteRole(role)
                                                            "
                                                        >
                                                            Видалити
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>

                                            <v-dialog
                                                v-model="
                                                    isDeleteRoleConfirmationModal
                                                "
                                                max-width="600px"
                                            >
                                                <v-card>
                                                    <v-card-text>
                                                        <span class="text-h5"
                                                            >Ви успішно видалили
                                                            {{
                                                                role.name
                                                            }}</span
                                                        >
                                                    </v-card-text>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn
                                                            color="green-darken-1"
                                                            text
                                                            @click="closeDeleteRoleConfirmationModal"
                                                        >
                                                            Закрити
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                            <!--End Delete Roles-->
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-text>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-card-text>
                    <!--End Cards Roles-->
                    <!--Pagination Roles-->
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
                    <!--End Pagination Roles-->
                </div>
            </v-card>
        </v-container>
    </v-layout>
</template>

<script setup>
import { onMounted, ref } from "vue";
import AddRole from "@/components/admin/roles/AddRole.vue";
import { removeAttribute } from "@/mixins/removeAttribute";

const isLoader = ref(false);

onMounted(() => {
    getRoles();
    getAllPermissions();
});

// Load Roles ========================================
const paginationCurrentPage = ref(null);
const paginationLastPage = ref(null);
const paginationRoles = ref(null);

function getRoles() {
    isLoader.value = true;
    let params = {
        // perPage: permissionPerPage.value,
        // search: searchRolesInput.value,
        // sortBy: sortRolesBy.value,
        // sortDirection: sortRolesDirection.value,
    };
    axios
        .get(
            "/admin/get-roles-pagination?page=" + paginationCurrentPage.value,
            {
                params,
            }
        )
        .then(({ data }) => {
            paginationRoles.value = data.roles.data.filter(
                (r) => r.name !== "Супер Адміністратор"
            );
            paginationCurrentPage.value = data.roles.current_page;
            paginationLastPage.value = data.roles.last_page;
            isLoader.value = false;
        })
        .catch((error) => {
            console.log('error', error);
        });
}

// Load Permissions ========================================
const allPermissions = ref(null);
function getAllPermissions() {
    axios
        .get("/admin/get-all-permissions")
        .then(({ data }) => {
            allPermissions.value = data.permissions;
        })
        .catch((error) => {
            console.log('error', error);
        });
}

// Edit Role Permissions ===============================================
const isEditRolePermissionsModal = ref(false);
const isEditRolePermissionsConfirmationModal = ref(false);
const isEditRolePermissionsModalLoader = ref(false);
const permissionsRules = ref([
    (v) => typeof v[0] !== "object" || "Ви не змінили дозволи ролей",
    (v) => v.length !== 0 || "Виберіть хоча б один дозвіл",
]);
const valid = ref(true);
const form = ref(null);
function handleSubmit(role) {
    form.value[0].validate();
    if (!valid.value) {
        return;
    }
    editRolePermissions(role);
}

function editRolePermissions(role) {
    isEditRolePermissionsModalLoader.value = true;
    axios
        .post(`/admin/edit-role-permissions`, {
            id: role.id,
            permissions: role.permissions,
        })
        .then(({ data }) => {
            isEditRolePermissionsModal.value = false;
            isEditRolePermissionsModalLoader.value = false;
            isEditRolePermissionsConfirmationModal.value = true;
        })
        .catch((error) => {
            console.log('error', error);
        })
        .finally(() => {
            isEditRolePermissionsModalLoader.value = false;
        });
}

function closeEditRolePermissionsConfirmationModal() {
    isEditRolePermissionsConfirmationModal.value = false;
    getRoles();
    removeAttribute()
}

// Delete Role ===============================================
const isDeleteRoleModal = ref(false);
const isDeleteRoleConfirmationModal = ref(false);
const isDeleteRoleModalLoader = ref(false);

function deleteRole(role) {
    isDeleteRoleModalLoader.value = true;
    axios
        .delete(`/admin/delete-role/${role.id}`)
        .then(({ data }) => {
            isDeleteRoleModal.value = false;
            isDeleteRoleModalLoader.value = false;
            isDeleteRoleConfirmationModal.value = true;
        })
        .catch((error) => {
            console.log('error', error);
        })
        .finally(() => {
            isDeleteRoleModalLoader.value = false;
        });
}

function closeDeleteRoleConfirmationModal() {
    isDeleteRoleConfirmationModal.value = false;
    getRoles();
    removeAttribute()

}

// Pagination ======================================
function onPageChange() {
    getRoles();
}
</script>

<style scoped></style>
