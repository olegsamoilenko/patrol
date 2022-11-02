<template>
    <v-layout column>
        <v-container fluid>
            <v-card>
                <v-card-title> Дозволи користувачів</v-card-title>
                <!--Add Permission-->
                <add-permission
                    :all-roles="allRoles"
                    @get-permissions="getPermissions"
                />
                <!--End Add Permission-->
                <v-card-title> Всі дозволи</v-card-title>
                <!--Filter Permissions-->
                <v-card-text>
                    <v-row>
                        <v-col cols="5" sm="6">
                            <v-select
                                v-model="permissionPerPage"
                                :items="permissionPerPageMap"
                                label="На сторінці"
                            >
                            </v-select>
                        </v-col>
                        <v-col cols="7" sm="6">
                            <v-select
                                v-model="sortPermissions"
                                :items="sortPermissionsMap"
                                label="Сортувати за..."
                            >
                            </v-select>
                        </v-col>
                        <v-col cols="12" class="relative">
                            <v-text-field
                                v-model="searchPermissionsInput"
                                variant="solo"
                                label="Пошук Прав"
                                append-inner-icon="mdi:mdi-magnify"
                                single-line
                                hide-details
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>
                <!--End Filter Permissions-->
                <v-card-text v-if="isLoader">
                    <v-col cols="12" class="d-flex justify-center">
                        <v-progress-circular
                            :size="50"
                            indeterminate
                            color="green"
                        ></v-progress-circular>
                    </v-col>
                </v-card-text>
                <!--Cards Permissions-->
                <div v-else>
                    <v-card-text>
                        <v-expansion-panels>
                            <v-expansion-panel
                                v-for="(permission, i) in paginationPermissions"
                                :key="i"
                                elevation="1"
                            >
                                <v-expansion-panel-title>
                                    <v-row>
                                        <v-col
                                            cols="12"
                                            class="d-flex align-self-center"
                                        >
                                            <span>{{ permission.name }}</span>
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-title>
                                <v-expansion-panel-text>
                                    <v-row>
                                        <v-col
                                            cols="12"
                                            class="d-flex justify-center"
                                        >
                                            <v-chip
                                                v-for="role in permission.roles"
                                                :key="role.id"
                                                density="comfortable"
                                            >
                                                {{ role.name }}</v-chip
                                            >
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            class="d-flex justify-center"
                                        >
                                            <!--Edit Permission Roles-->
                                            <v-btn
                                                icon
                                                color="green"
                                                class="mr-5"
                                                width="var(--v-button-width)"
                                                height="var(--v-button-height)"
                                                @click="
                                                    isEditPermissionRolesModal = true
                                                "
                                            >
                                                <v-icon
                                                >mdi:mdi-pencil</v-icon
                                                >
                                            </v-btn>

                                            <v-dialog
                                                v-model="
                                                    isEditPermissionRolesModal
                                                "
                                                max-width="600px"
                                            >
                                                <v-form
                                                    ref="form"
                                                    v-model="valid"
                                                    @submit.prevent="
                                                        handleSubmit(permission)
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
                                                                            permission.roles
                                                                        "
                                                                        :items="
                                                                            allRoles
                                                                        "
                                                                        item-title="name"
                                                                        item-value="id"
                                                                        label="Дозволи"
                                                                        :rules="
                                                                            rolesRules
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
                                                                        isEditPermissionRolesModalLoader
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
                                                                    isEditPermissionRolesModal = false;
                                                                    isEditPermissionRolesModalLoader = false;
                                                                "
                                                            >
                                                                Відмінити
                                                            </v-btn>
                                                            <v-btn
                                                                color="green-darken-1"
                                                                text
                                                                :disabled="
                                                                    isEditPermissionRolesModalLoader
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
                                                    isEditPermissionRolesConfirmationModal
                                                "
                                                max-width="600px"
                                            >
                                                <v-card>
                                                    <v-card-text>
                                                        <span class="text-h5"
                                                        >Ви успішно
                                                            відредагували
                                                            {{
                                                                permission.name
                                                            }}</span
                                                        >
                                                    </v-card-text>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn
                                                            color="green-darken-1"
                                                            text
                                                            @click="
                                                                getPermissions();
                                                                isEditPermissionRolesConfirmationModal = false;
                                                            "
                                                        >
                                                            Закрити
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                            <!--End Edit Permission Roles-->
                                            <!--Delete Permissions-->
                                            <v-btn
                                                icon
                                                color="red"
                                                width="var(--v-button-width)"
                                                height="var(--v-button-height)"
                                                @click="
                                                    isDeletePermissionModal = true
                                                "
                                            >
                                                <v-icon>mdi:mdi-delete</v-icon>
                                            </v-btn>

                                            <v-dialog
                                                v-model="isDeletePermissionModal"
                                                max-width="600px"
                                            >
                                                <v-card>
                                                    <v-card-text>
                                                        <span class="text-h5"
                                                        >Бажаєте видалити
                                                            {{
                                                                permission.name
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
                                                                    isDeletePermissionModalLoader
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
                                                                isDeletePermissionModal = false;
                                                                isDeletePermissionModalLoader = false;
                                                            "
                                                        >
                                                            Відмінити
                                                        </v-btn>
                                                        <v-btn
                                                            color="green-darken-1"
                                                            text
                                                            :disabled="
                                                                isDeletePermissionModalLoader
                                                            "
                                                            @click="
                                                                deleteRole(permission)
                                                            "
                                                        >
                                                            Видалити
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>

                                            <v-dialog
                                                v-model="
                                                    isDeletePermissionConfirmationModal
                                                "
                                                max-width="600px"
                                            >
                                                <v-card>
                                                    <v-card-text>
                                                        <span class="text-h5"
                                                        >Ви успішно видалили
                                                            {{
                                                                permission.name
                                                            }}</span
                                                        >
                                                    </v-card-text>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn
                                                            color="green-darken-1"
                                                            text
                                                            @click="
                                                                getPermissions();
                                                                isDeletePermissionConfirmationModal = false;
                                                            "
                                                        >
                                                            Закрити
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                            <!--End Delete Permissions-->
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-text>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-card-text>
                    <!--End Cards Permissions-->
                    <!--Pagination Permissions-->
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
                    <!--End Pagination Permissions-->
                </div>
            </v-card>
        </v-container>
    </v-layout>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import AddPermission from "@/components/admin/permissions/AddPermission.vue";
const isLoader = ref(false);

onMounted(() => {
    getPermissions();
    getAllRoles()
});



// Filter Permissions ========================================
const permissionPerPage = ref("10");
const permissionPerPageMap = ref([
    { value: "10", title: "10" },
    { value: "20", title: "20" },
    { value: "50", title: "50" },
]);
watch(permissionPerPage, () => {
    paginationCurrentPage.value = 1;
    getPermissions();
});

const sortPermissions = ref("surnameAsc");
const sortPermissionsMap = ref([
    { value: "surnameAsc", title: "За Ім'ям   А...Я" },
    { value: "surnameDesc", title: "За Ім'ям   Я...А" },
    { value: "dateAsc", title: "За датою   1...9" },
    { value: "dateDesc", title: "За датою   9...1" },
]);

const sortPermissionsBy = ref("created_at");
const sortPermissionsDirection = ref("ASC");
watch(sortPermissions, () => {
    if (sortPermissions.value === "surnameAsc") {
        sortPermissionsBy.value = "name";
        sortPermissionsDirection.value = "ASC";
    } else if (sortPermissions.value === "surnameDesc") {
        sortPermissionsBy.value = "name";
        sortPermissionsDirection.value = "DESC";
    } else if (sortPermissions.value === "dateAsc") {
        sortPermissionsBy.value = "created_at";
        sortPermissionsDirection.value = "ASC";
    } else if (sortPermissions.value === "dateDesc") {
        sortPermissionsBy.value = "created_at";
        sortPermissionsDirection.value = "DESC";
    }
    paginationCurrentPage.value = 1;
    getPermissions();
});

const searchPermissionsInput = ref(null);
watch(searchPermissionsInput, () => {
    paginationCurrentPage.value = 1;
    getPermissions();
});

// Load Permissions ========================================
const paginationCurrentPage = ref(null);
const paginationLastPage = ref(null);
const paginationPermissions = ref(null);

function getPermissions() {
    isLoader.value = true;
    let params = {
        perPage: permissionPerPage.value,
        search: searchPermissionsInput.value,
        sortBy: sortPermissionsBy.value,
        sortDirection: sortPermissionsDirection.value,
    };
    axios
        .get(
            "/admin/get-permissions-pagination?page=" +
            paginationCurrentPage.value,
            { params }
        )
        .then(({ data }) => {
            paginationPermissions.value = data.permissions.data;
            paginationCurrentPage.value = data.permissions.current_page;
            paginationLastPage.value = data.permissions.last_page;
            console.log(paginationPermissions.value);
            isLoader.value = false;
        })
        .catch((error) => {
            console.log(error);
        });
}

// Load Roles ========================================
const allRoles = ref(null);
function getAllRoles() {
    axios
        .get("/admin/get-all-roles")
        .then(({ data }) => {
            allRoles.value = data.roles.filter(r => r.name !== 'Супер Адміністратор');
            console.log(allRoles.value);
        })
        .catch((error) => {
            console.log(error);
        });
}

// Edit Permission Roles ===============================================
const isEditPermissionRolesModal = ref(false);
const isEditPermissionRolesConfirmationModal = ref(false);
const isEditPermissionRolesModalLoader = ref(false);
const rolesRules = ref([
    (v) => typeof v[0] !== "object" || "Ви не змінили ролі дозволів",
    (v) => v.length !== 0 || "Виберіть хоча б одну роль",
]);
const valid = ref(true);
const form = ref(null);
function handleSubmit(permission) {
    form.value[0].validate();
    if (!valid.value) {
        return;
    }
    editPermissionRoles(permission);
}

function editPermissionRoles(permission) {
    isEditPermissionRolesModalLoader.value = true;
    console.log(permission);
    axios
        .post(`/admin/edit-permission-roles`, {
            id: permission.id,
            roles: permission.roles,
        })
        .then(({ data }) => {
            console.log("data", data);
            isEditPermissionRolesModal.value = false;
            isEditPermissionRolesModalLoader.value = false;
            isEditPermissionRolesConfirmationModal.value = true;
        })
        .catch(({ response }) => {
            console.log(response);
        })
        .finally(() => {
            isEditPermissionRolesModalLoader.value = false;
        });
}

// Delete Permission ===============================================
const isDeletePermissionModal = ref(false);
const isDeletePermissionConfirmationModal = ref(false);
const isDeletePermissionModalLoader = ref(false);

function deleteRole(permission) {
    isDeletePermissionModalLoader.value = true;
    console.log(permission);
    axios
        .delete(`/admin/delete-permission/${permission.id}`)
        .then(({ data }) => {
            console.log("data", data);
            isDeletePermissionModal.value = false;
            isDeletePermissionModalLoader.value = false;
            isDeletePermissionConfirmationModal.value = true;
        })
        .catch(({ response }) => {
            console.log(response);
        })
        .finally(() => {
            isDeletePermissionModalLoader.value = false;
        });
}

// Pagination ======================================
function onPageChange() {
    getPermissions();
}
</script>

<style scoped></style>
