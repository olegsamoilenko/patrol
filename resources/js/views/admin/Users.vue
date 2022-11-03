<template>
    <v-layout column>
        <v-container fluid>
            <v-card>
                <v-card-title> Користувачі</v-card-title>
                <!--Filter Users------------------------------------------------------------>
                <v-card-text>
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
                <!--End Filter Users--------------------------------------------------------->

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
                        <!--Card Users------------------------------------------------------------------>
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
                                                        user.is_activated === 'Ні',
                                                    'bg-secondary':
                                                        role.name ===
                                                        'Адміністратор',
                                                }"
                                                class="mr-2"
                                            >
                                                {{ role.name }}
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

                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="3"
                                            class="mb-3 d-md-flex justify-md-end"
                                        >
                                            <!-- Activate User-------------------------------------------------------------->
                                            <v-btn
                                                v-if="
                                                    (user.is_activated === 'Ні' &&
                                                        store.user.roles.find(
                                                            (r) =>
                                                                r.name ===
                                                                'Супер Адміністратор'
                                                        )) ||
                                                    (user.is_activated === 'Ні' &&
                                                        store.checkPermission(
                                                            'Користувач активувати'
                                                        ))
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
                                                            @click="closeActivateUserConfirmModal"
                                                        >
                                                            Закрити
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                            <!--End Activate User----------------------------------------------------------->
                                        </v-col>

                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="3"
                                            class="mb-3 d-sm-flex justify-sm-end"
                                        >
                                            <!--Edit User--------------------------------------------->
                                            <v-btn
                                                v-if="
                                                    store.user.roles.find(
                                                        (r) =>
                                                            r.name ===
                                                            'Супер Адміністратор'
                                                    ) ||
                                                    store.checkPermission(
                                                        'Користувач редагувати'
                                                    )
                                                "
                                                icon
                                                color="green"
                                                class="mr-5"
                                                width="var(--v-button-width)"
                                                height="var(--v-button-height)"
                                                @click="isEditUserModal = true"
                                            >
                                                <v-icon>mdi:mdi-pencil</v-icon>
                                            </v-btn>

                                            <v-dialog
                                                    v-model="isEditUserModal"
                                                    max-width="600px"
                                                    scrollable
                                                >
                                                    <v-card max-height="100vh">
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
                                                                Редагувати
                                                                користувача</span
                                                            >
                                                            </v-card-title>
                                                            <v-card-text>
                                                                <v-row>
                                                                    <v-col
                                                                        cols="12"
                                                                    >
                                                                        <v-text-field
                                                                            v-model="user.name"
                                                                            label="Ім'я"
                                                                            :rules="[v => !!v || 'Ім`я обов`язкове']"
                                                                        >
                                                                            <template
                                                                                #details
                                                                            >
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
                                                                    <v-col
                                                                        cols="12"
                                                                    >
                                                                        <v-text-field
                                                                            v-model="
                                                                            user.surname
                                                                        "
                                                                            label="Прізвище"
                                                                            :rules="[v => !!v || 'Прізвище обов`язкове']"
                                                                        >
                                                                            <template
                                                                                #details
                                                                            >
                                                                            <span
                                                                                class="text-error text-caption"
                                                                                v-if="
                                                                                    validationErrorsFromBase.surname
                                                                                "
                                                                            >{{
                                                                                    validationErrorsFromBase
                                                                                        .surname[0]
                                                                                }}</span
                                                                            >
                                                                                <v-spacer />
                                                                            </template>
                                                                        </v-text-field>
                                                                    </v-col>
                                                                    <v-col
                                                                        cols="12"
                                                                    >
                                                                        <v-text-field
                                                                            v-model="
                                                                            user.email
                                                                        "
                                                                            label="Email"
                                                                            :rules="[v => !!v || 'Email обов`язковий']"
                                                                        >
                                                                            <template
                                                                                #details
                                                                            >
                                                                            <span
                                                                                class="text-error text-caption"
                                                                                v-if="
                                                                                    validationErrorsFromBase.email
                                                                                "
                                                                            >{{
                                                                                    validationErrorsFromBase
                                                                                        .email[0]
                                                                                }}</span
                                                                            >
                                                                                <v-spacer />
                                                                            </template>
                                                                        </v-text-field>
                                                                    </v-col>
                                                                    <v-col
                                                                        cols="12"
                                                                    >
                                                                    <!--TODO: Регулярку на телефон-->
                                                                        <v-text-field
                                                                            v-model="
                                                                            user.phone
                                                                        "
                                                                            label="Телефон"
                                                                            :rules="[v => !!v || 'Телефон обов`язковий']"
                                                                        >
                                                                            <template
                                                                                #details
                                                                            >
                                                                            <span
                                                                                class="text-error text-caption"
                                                                                v-if="
                                                                                    validationErrorsFromBase.phone
                                                                                "
                                                                            >{{
                                                                                    validationErrorsFromBase
                                                                                        .phone[0]
                                                                                }}</span
                                                                            >
                                                                                <v-spacer />
                                                                            </template>
                                                                        </v-text-field>
                                                                    </v-col>
                                                                    <v-col
                                                                        cols="12"
                                                                    >
                                                                        <v-card-title
                                                                            as="div"
                                                                        >
                                                                            <div
                                                                                class="text-h6"
                                                                            >
                                                                                Активація
                                                                            </div>
                                                                        </v-card-title>
                                                                        <v-radio-group
                                                                            v-model="user.is_activated"
                                                                            inline
                                                                        >
                                                                            <v-radio
                                                                                label="Активований"
                                                                                :value="
                                                                                'Так'
                                                                            "
                                                                            ></v-radio>
                                                                            <v-radio
                                                                                label="Не активований"
                                                                                :value="
                                                                                'Ні'
                                                                            "
                                                                            ></v-radio>
                                                                        </v-radio-group>
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
                                                                            required
                                                                            :rules="[(v) => v.length !== 0 || 'Виберіть хоча б одну роль']"
                                                                        >
                                                                            <template
                                                                                #details
                                                                            >
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
                                                                <v-col
                                                                    cols="12"
                                                                    class="d-flex justify-center"
                                                                >
                                                                    <v-progress-circular
                                                                        v-if="
                                                                        isEditUserModalLoader
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
                                                                    isEditUserModal = false;
                                                                    isEditUserModalLoader = false;
                                                                "
                                                                >
                                                                    Відмінити
                                                                </v-btn>
                                                                <v-btn
                                                                    color="green-darken-1"
                                                                    text
                                                                    :disabled="
                                                                    isEditUserModalLoader
                                                                "
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
                                                v-model="
                                                    isEditUserConfirmationModal
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
                                                            @click="closeEditUserConfirmModal"
                                                        >
                                                            Закрити
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                            <!--End Edit User------------------------------------------------------>

                                            <!--Delete User-------------------------------------------------->
                                            <v-btn
                                                v-if="
                                                    store.user.roles.find(
                                                        (r) =>
                                                            r.name ===
                                                            'Супер Адміністратор'
                                                    ) ||
                                                    store.checkPermission(
                                                        'Користувач видалити'
                                                    )
                                                "
                                                icon
                                                color="red"
                                                width="var(--v-button-width)"
                                                height="var(--v-button-height)"
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
                                                            @click="closeDeleteUserConfirmModal"
                                                        >
                                                            Закрити
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                            <!--End Delete User----------------------------------------------->
                                        </v-col>
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
import { useAuthStore } from "@/stores/auth";
import { removeAttribute } from "@/mixins/removeAttribute";

const store = useAuthStore();

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
        .get(`/admin/get-users?page=` + paginationCurrentPage.value, { params })
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
        .get("/admin/get-all-roles")
        .then(({ data }) => {
            allUsersRoles.value = data.roles;
            console.log("data", data);
        })
        .catch(({ response }) => {
            console.log(response);
        })
        .finally(() => {});
}

// Activate User ===============================================
const isActivateUserModal = ref(false);
const isActivateUserConfirmationModal = ref(false);
const isActivateUserModalLoader = ref(false);

function activateUser(user) {
    isActivateUserModalLoader.value = true;
    axios
        .post(`/admin/activate-user/${user.id}`)
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
function closeActivateUserConfirmModal() {
    isActivateUserConfirmationModal.value = false;
    getUsers()
    removeAttribute()
}

// Edit User ===============================================
const isEditUserModal = ref(false);
const isEditUserConfirmationModal = ref(false);
const isEditUserModalLoader = ref(false);
const validationErrorsFromBase = ref({});
const valid = ref(true);
const form = ref(null);
function handleSubmit(user) {
    form.value[0].validate();
    if (!valid.value) {
        return;
    }
    editUser(user);
}

function editUser(user) {
    isEditUserModalLoader.value = true;
    console.log(user);
    let params = {
        name: user.name,
        surname: user.surname,
        phone: user.phone,
        email: user.email,
        roles: user.roles,
        is_activated: user.is_activated,
    };
    axios
        .post(`/admin/edit-user/${user.id}`, params)
        .then(({ data }) => {
            console.log("data", data);
            isEditUserModal.value = false;
            isEditUserModalLoader.value = false;
            isEditUserConfirmationModal.value = true;
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
            isEditUserModalLoader.value = false;
        });
}

function closeEditUserConfirmModal() {
    isEditUserConfirmationModal.value = false;
    getUsers()
    removeAttribute()
}



// Delete User ===============================================
const isDeleteUserModal = ref(false);
const isDeleteUserConfirmationModal = ref(false);
const isDeleteUserModalLoader = ref(false);

function deleteUser(user) {
    isDeleteUserModalLoader.value = true;
    axios
        .delete(`/admin/delete-user/${user.id}`)
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

function closeDeleteUserConfirmModal() {
    isDeleteUserConfirmationModal.value = false;
    getUsers()
    removeAttribute()
}

// Pagination ======================================
function onPageChange() {
    getUsers();
}


</script>

<style>
.v-messages {
    flex: 0 1 auto;
}
</style>
