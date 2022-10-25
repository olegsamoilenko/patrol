<template>
    <v-layout column>
        <v-container fluid>
            <v-card>
                <v-card-title> Користувачі</v-card-title>
                <v-card-text>
                    <!--Filter Users-->
                    <v-row>
                        <v-col cols="12" sm="6">
                            <v-select
                                v-model="selectedUsersRole"
                                :items="allUsersRolesMap"
                                :item-title="(item) => item.name"
                                :item-value="(item) => item.slug"
                                placeholder="Всі ролі"
                            >
                            </v-select>
                        </v-col>
                        <v-col cols="12" sm="6">
                            <v-select
                                v-model="sortUsers"
                                :items="sortUsersMap"
                                placeholder="Дата реєстрації"
                            >
                            </v-select>
                        </v-col>
                        <v-col cols="12" class="relative">
                            <v-text-field
                                v-model="searchUsersInput"
                                variant="solo"
                                label="Пошук Користувача"
                                append-inner-icon="mdi:mdi-magnify"
                                single-line
                                hide-details
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>

                <!--End Filter Users-->

                <v-card-text v-if="isLoader">
                    <v-col cols="12" class="d-flex justify-center">
                        <v-progress-circular
                            :size="50"
                            indeterminate
                            color="green"
                        ></v-progress-circular>
                    </v-col>
                </v-card-text>
                <div v-else>
                    <v-card-text>

                        <!--Card Users-->
                        <v-expansion-panels>
                            <v-expansion-panel
                                v-for="(user, i) in paginationUsers"
                                :key="i"
                                elevation="1"
                            >
                                <v-expansion-panel-title>
                                    <v-row no-gutters>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            class="align-self-center mb-3"
                                        >
                                            {{ user.name + " " + user.surname }}
                                        </v-col>
                                        <v-spacer></v-spacer>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            class="text-start text-sm-end"
                                        >
                                            <v-chip
                                                v-for="role in user.roles"
                                                :key="role.id"
                                                :class="{
                                                    'bg-warning':
                                                        role.slug === 'none',
                                                    'bg-secondary':
                                                        role.slug === 'admin',
                                                }"
                                                class="mr-2"
                                            >
                                                {{
                                                    role.slug === "none"
                                                        ? "Не активований"
                                                        : role.name
                                                }}
                                            </v-chip>
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-title>
                                <!--TODO: Адаптивный дизайн немного гуляет-->
                                <v-expansion-panel-text>
                                    <v-row no-gutters>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="2"
                                            class="mb-3"
                                        >
                                            <v-icon>mdi:mdi-phone</v-icon>
                                            {{ user.phone }}
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                            class="mb-3 text-sm-end"
                                        >
                                            <v-icon>mdi:mdi-email</v-icon>
                                            {{ user.email }}
                                        </v-col>

                                        <!--TODO: Налаштувати доступ-->
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="3"
                                            class="mb-3 d-md-flex justify-md-end"
                                        >
                                            <!-- Activate User-->
                                            <v-btn
                                                v-if="
                                                    Object.values(user.roles)
                                                        .length !== 0 &&
                                                    user.roles.find(
                                                        (r) => r.slug === 'none'
                                                    )
                                                "
                                                color="warning"
                                                @click="
                                                    isActivateUserModal = true
                                                "
                                                >Активувати
                                            </v-btn>
                                            <v-dialog
                                                v-model="isActivateUserModal"
                                                max-width="600px"
                                            >
                                                <v-card>
                                                    <v-card-text>
                                                        <span class="text-h5"
                                                            >Бажаєте активувати
                                                            {{
                                                                user.name +
                                                                " " +
                                                                user.surname
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
                                                                    isActivateUserModalLoader
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
                                                                isActivateUserModal = false;
                                                                isActivateUserModalLoader = false;
                                                            "
                                                        >
                                                            Відмінити
                                                        </v-btn>
                                                        <v-btn
                                                            color="green-darken-1"
                                                            text
                                                            :disabled="
                                                                isActivateUserModalLoader
                                                            "
                                                            @click="
                                                                activateUser(
                                                                    user
                                                                )
                                                            "
                                                        >
                                                            Активувати
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>

                                            <v-dialog
                                                v-model="
                                                    isActivateUserConfirmationModal
                                                "
                                                max-width="600px"
                                            >
                                                <v-card>
                                                    <v-card-text>
                                                        <span class="text-h5"
                                                            >Ви успішно
                                                            активували
                                                            {{
                                                                user.name +
                                                                " " +
                                                                user.surname
                                                            }}</span
                                                        >
                                                    </v-card-text>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn
                                                            color="green-darken-1"
                                                            text
                                                            @click="
                                                                getUsers();
                                                                isActivateUserConfirmationModal = false;
                                                            "
                                                        >
                                                            Закрити
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                            <!--End Activate User-->
                                        </v-col>
                                        <!--TODO: Налаштувати доступ тільки SuperAdmin-->
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="3"
                                            class="mb-3 d-sm-flex justify-sm-end"
                                        >
                                            <!--Edit Roles User-->

                                            <v-btn
                                                icon
                                                color="green"
                                                class="mr-5"
                                                @click="
                                                    isEditUserRolesModal = true
                                                "
                                            >
                                                <v-icon>mdi:mdi-pencil</v-icon>
                                            </v-btn>

                                            <v-dialog
                                                v-model="isEditUserRolesModal"
                                                max-width="600px"
                                                persistent
                                            >
                                                <v-form
                                                    ref="form"
                                                    v-model="valid"
                                                    @submit.prevent="
                                                        handleSubmit(user)
                                                    "
                                                >
                                                    <v-card max-width="600px">
                                                        <v-card-title>
                                                            <span
                                                                class="text-h5"
                                                            >
                                                                Редагувати роль
                                                                користувача</span
                                                            >
                                                        </v-card-title>
                                                        <v-card-text>
                                                            <v-row>
                                                                <v-col
                                                                    cols="12"
                                                                >
                                                                    <v-text-field
                                                                        v-model="
                                                                            user.name
                                                                        "
                                                                        label="Ім'я"
                                                                        disabled
                                                                    ></v-text-field>
                                                                </v-col>
                                                                <v-col
                                                                    cols="12"
                                                                >
                                                                    <v-text-field
                                                                        v-model="
                                                                            user.surname
                                                                        "
                                                                        label="Прізвище"
                                                                        disabled
                                                                    ></v-text-field>
                                                                </v-col>
                                                                <v-col
                                                                    cols="12"
                                                                >
                                                                    <v-text-field
                                                                        v-model="
                                                                            user.email
                                                                        "
                                                                        label="Email"
                                                                        disabled
                                                                    ></v-text-field>
                                                                </v-col>
                                                                <v-col
                                                                    cols="12"
                                                                >
                                                                    <v-text-field
                                                                        v-model="
                                                                            user.phone
                                                                        "
                                                                        label="Телефон"
                                                                        disabled
                                                                    ></v-text-field>
                                                                </v-col>
                                                                <v-col
                                                                    cols="12"
                                                                >
                                                                    <v-select
                                                                        v-model="
                                                                            user.roles
                                                                        "
                                                                        :items="
                                                                            allUsersRoles
                                                                        "
                                                                        :item-title="
                                                                            (
                                                                                item
                                                                            ) =>
                                                                                item.name
                                                                        "
                                                                        :item-value="
                                                                            (
                                                                                item
                                                                            ) =>
                                                                                item.id
                                                                        "
                                                                        placeholder="Ролі"
                                                                        multiple
                                                                        :rules="
                                                                            rolesRules
                                                                        "
                                                                        required
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
                                                                        isEditUserRolesModalLoader
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
                                                                    isEditUserRolesModal = false;
                                                                    isEditUserRolesModalLoader = false;
                                                                "
                                                            >
                                                                Відмінити
                                                            </v-btn>
                                                            <v-btn
                                                                color="green-darken-1"
                                                                text
                                                                :disabled="
                                                                    isEditUserRolesModalLoader
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
                                                    isEditUserRolesConfirmationModal
                                                "
                                                max-width="600px"
                                            >
                                                <v-card>
                                                    <v-card-text>
                                                        <span class="text-h5"
                                                            >Ви успішно
                                                            відредагували
                                                            {{
                                                                user.name +
                                                                " " +
                                                                user.surname
                                                            }}</span
                                                        >
                                                    </v-card-text>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn
                                                            color="green-darken-1"
                                                            text
                                                            @click="
                                                                getUsers();
                                                                isEditUserRolesConfirmationModal = false;
                                                            "
                                                        >
                                                            Закрити
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                            <!--End Edit Roles User-->

                                            <!--Delete User-->
                                            <v-btn
                                                icon
                                                color="red"
                                                @click="
                                                    isDeleteUserModal = true
                                                "
                                            >
                                                <v-icon>mdi:mdi-delete</v-icon>
                                            </v-btn>

                                            <v-dialog
                                                v-model="isDeleteUserModal"
                                                max-width="600px"
                                            >
                                                <v-card>
                                                    <v-card-text>
                                                        <span class="text-h5"
                                                            >Бажаєте видалити
                                                            {{
                                                                user.name +
                                                                " " +
                                                                user.surname
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
                                                                    isDeleteUserModalLoader
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
                                                                isDeleteUserModal = false;
                                                                isDeleteUserModalLoader = false;
                                                            "
                                                        >
                                                            Відмінити
                                                        </v-btn>
                                                        <v-btn
                                                            color="green-darken-1"
                                                            text
                                                            :disabled="
                                                                isDeleteUserModalLoader
                                                            "
                                                            @click="
                                                                deleteUser(user)
                                                            "
                                                        >
                                                            Видалити
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>

                                            <v-dialog
                                                v-model="
                                                    isDeleteUserConfirmationModal
                                                "
                                                max-width="600px"
                                            >
                                                <v-card>
                                                    <v-card-text>
                                                        <span class="text-h5"
                                                            >Ви успішно видалили
                                                            {{
                                                                user.name +
                                                                " " +
                                                                user.surname
                                                            }}</span
                                                        >
                                                    </v-card-text>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn
                                                            color="green-darken-1"
                                                            text
                                                            @click="
                                                                getUsers();
                                                                isDeleteUserConfirmationModal = false;
                                                            "
                                                        >
                                                            Закрити
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                        </v-col>
                                        <!--End Delete User-->
                                    </v-row>
                                </v-expansion-panel-text>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-card-text>
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
                </div>
            </v-card>
        </v-container>
    </v-layout>
</template>

<script setup>
import { onMounted, ref, watch, computed } from "vue";

onMounted(() => {
    getUserRoles();
    getUsers();
});

const isLoader = ref(false);

// Filter Users ========================================

const allUsersRolesMap = computed(() => [
    { name: "Всі ролі", slug: null },
    ...allUsersRoles.value,
]);
const selectedUsersRole = ref(null);

const sortUsers = ref("dateAsc");
const sortUsersMap = ref([
    { value: "surnameAsc", title: "За Прізвищем   А...Я" },
    { value: "surnameDesc", title: "За Прізвищем   Я...А" },
    { value: "dateAsc", title: "За датою реєстрації   1...9" },
    { value: "dateDesc", title: "За датою реєстрації   9...1" },
]);
const sortUsersBy = ref("created_at");
const sortUsersDirection = ref("ASC");

watch(sortUsers, () => {
    if (sortUsers.value === "surnameAsc") {
        sortUsersBy.value = "surname";
        sortUsersDirection.value = "ASC";
    } else if (sortUsers.value === "surnameDesc") {
        sortUsersBy.value = "surname";
        sortUsersDirection.value = "DESC";
    } else if (sortUsers.value === "dateAsc") {
        sortUsersBy.value = "created_at";
        sortUsersDirection.value = "ASC";
    } else if (sortUsers.value === "dateDesc") {
        sortUsersBy.value = "created_at";
        sortUsersDirection.value = "DESC";
    }
    paginationCurrentPage.value = 1;
    getUsers();
});

watch(selectedUsersRole, () => {
    paginationCurrentPage.value = 1;
    getUsers();
    console.log(selectedUsersRole.value);
});

const searchUsersInput = ref(null);
watch(searchUsersInput, () => {
    paginationCurrentPage.value = 1;
    getUsers();
    console.log(selectedUsersRole.value);
});

// Load Users ========================================

const paginationUsers = ref(null);
const paginationCurrentPage = ref(null);
const paginationLastPage = ref(null);
const allUsersRoles = ref([]);

function getUsers() {
    isLoader.value = true;
    let params = {
        role: selectedUsersRole.value,
        search: searchUsersInput.value,
        sortBy: sortUsersBy.value,
        sortDirection: sortUsersDirection.value,
    };
    axios
        .get(`/get-users?page=` + paginationCurrentPage.value, { params })
        .then(({ data }) => {
            paginationUsers.value = data.users.data;
            paginationCurrentPage.value = data.users.current_page;
            paginationLastPage.value = data.users.last_page;
            console.log("data", data.users);
        })
        .catch(({ response }) => {
            console.log(response);
        })
        .finally(() => {
            isLoader.value = false;
        });
}

function getUserRoles() {
    axios
        .get("/get-user-roles")
        .then(({ data }) => {
            allUsersRoles.value = data.roles;
            console.log("data", data);
        })
        .catch(({ response }) => {
            console.log(response);
        })
        .finally(() => {});
}

// Pagination ======================================

function onPageChange() {
    getUsers();
}

// Activate User ===============================================

const isActivateUserModal = ref(false);
const isActivateUserConfirmationModal = ref(false);
const isActivateUserModalLoader = ref(false);

function activateUser(user) {
    isActivateUserModalLoader.value = true;
    axios
        .post(`/activate-user/${user.id}`)
        .then(({ data }) => {
            console.log("data", data);
            isActivateUserModal.value = false;
            isActivateUserModalLoader.value = false;
            isActivateUserConfirmationModal.value = true;
        })
        .catch(({ response }) => {
            console.log(response);
        })
        .finally(() => {
            isActivateUserModalLoader.value = false;
        });
}

// Edit User Roles ===============================================

const isEditUserRolesModal = ref(false);
const isEditUserRolesConfirmationModal = ref(false);
const isEditUserRolesModalLoader = ref(false);
const rolesRules = ref([
    (v) => typeof v[0] !== 'object' || "Ви не змінили ролі користувача",
    (v) => v.length !== 0 || "Виберіть хоча б одну роль",
    (v) =>
        !(v.find((id) => id === (allUsersRoles.value.find(role => role.slug === "none").id)) && v.length > 1) ||
        "Не можна вибирати роль 'Не активований' разом з іншими ролями",
]);
const valid = ref(true);
const form = ref(null);
function handleSubmit(user) {
    form.value[0].validate()
    if (!valid.value) {
        return;
    }
    editUserRoles(user)
}

function editUserRoles(user) {
    isEditUserRolesModalLoader.value = true;
    axios
        .post(`/edit-user-roles`, {id: user.id, roles: user.roles})
        .then(({ data }) => {
            console.log("data", data);
            isEditUserRolesModal.value = false;
            isEditUserRolesModalLoader.value = false;
            isEditUserRolesConfirmationModal.value = true;
        })
        .catch(({ response }) => {
            console.log(response);
        })
        .finally(() => {
            isEditUserRolesModalLoader.value = false;
        });
}

// Delete User ===============================================

const isDeleteUserModal = ref(false);
const isDeleteUserConfirmationModal = ref(false);
const isDeleteUserModalLoader = ref(false);

function deleteUser(user) {
    isDeleteUserModalLoader.value = true;
    axios
        .delete(`/delete-user/${user.id}`)
        .then(({ data }) => {
            console.log("data", data);
            isDeleteUserModal.value = false;
            isDeleteUserModalLoader.value = false;
            isDeleteUserConfirmationModal.value = true;
        })
        .catch(({ response }) => {
            console.log(response);
        })
        .finally(() => {
            isDeleteUserModalLoader.value = false;
        });
}
</script>

<style scoped></style>
