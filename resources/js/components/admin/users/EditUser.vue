<template>
    <!--Edit User--------------------------------------------->
    <v-btn
        v-if="store.user.roles.find((r) => r.name === 'Супер Адміністратор') ||
            store.checkPermission('Користувач редагувати')
        "
        icon
        color="green"
        class="mr-5"
        width="var(--v-button-width)"
        height="var(--v-button-height)"
        @click="isEditObjectModal = true"
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
                @submit.prevent="handleSubmit(user)"
            >
                <v-card
                    max-width="600px"
                >
                    <v-card-title>
                        <span class="text-h5">Редагувати користувача</span>
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="user.name"
                                    label="Ім'я"
                                    :rules="[(v) => !!v || 'Ім`я обов`язкове',
                                             (v) => /^[а-яА-Яа-щА-ЩЬьЮюЯяЇїІіЄєҐґ0-9]+$/.test(v) ||
                                                'Введіть Ім\'я українською',
                                             ]"
                                >
                                    <template #details>
                                        <span
                                            v-if="validationErrorsFromBase.name"
                                            class="text-error text-caption"
                                        >
                                            {{ validationErrorsFromBase.name[0] }}
                                        </span
                                        >
                                        <v-spacer/>
                                    </template>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="user.surname"
                                    label="Прізвище"
                                    :rules="[(v) => !!v || 'Прізвище обов`язкове',
                                            (v) => /^[а-яА-Яа-щА-ЩЬьЮюЯяЇїІіЄєҐґ0-9]+$/.test(v) ||
                                                'Введіть Прізвище українською',
                                            ]"
                                >
                                    <template #details>
                                        <span
                                            v-if="validationErrorsFromBase.surname "
                                            class="text-error text-caption"
                                        >
                                            {{ validationErrorsFromBase.surname[0] }}
                                        </span>
                                        <v-spacer/>
                                    </template>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="user.email"
                                    label="Email"
                                    :rules="[(v) => !!v || 'Email обов`язковий',
                                             (v) => /.+@.+\..+/.test(v) || 'Введіть коректний email']"
                                >
                                    <template #details>
                                        <span
                                            v-if="validationErrorsFromBase.email"
                                            class="text-error text-caption"
                                        >
                                            {{ validationErrorsFromBase.email[0] }}
                                        </span>
                                        <v-spacer/>
                                    </template>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="user.phone"
                                    label="Телефон"
                                    :rules="[(v) => !!v || 'Телефон обов`язковий',
                                             (v) => /^((\+?3)?8)?0\d{9}$/.test(v) || 'Введіть коректний номер телефону']"
                                >
                                    <template #details>
                                        <span
                                            v-if="validationErrorsFromBase.phone"
                                            class="text-error text-caption"
                                        >
                                            {{ validationErrorsFromBase.phone[0] }}
                                        </span>
                                        <v-spacer/>
                                    </template>
                                </v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-card-title as="div">
                                    <div class="text-h6">
                                        Активація
                                    </div>
                                </v-card-title>
                                <v-radio-group
                                    v-model="user.is_activated"
                                    inline
                                >
                                    <v-radio
                                        label="Активований"
                                        :value="'Так'"
                                    ></v-radio>
                                    <v-radio
                                        label="Не активований"
                                        :value="'Ні'"
                                    ></v-radio>
                                </v-radio-group>
                            </v-col>

                            <v-col cols="12">
                                <v-select
                                    v-model="user.roles"
                                    :items="allUserRoles"
                                    :item-title="(item) =>item.name"
                                    :item-value="(item) =>item.id"
                                    placeholder="Ролі"
                                    multiple
                                    required
                                    :rules="[(v) => v.length !==0 || 'Виберіть хоча б одну роль']"
                                >
                                    <template #details>
                                        <span
                                            class="text-error text-caption"
                                            v-if="validationErrorsFromBase.roles"
                                        >
                                            {{ validationErrorsFromBase.roles[0] }}
                                        </span>
                                        <v-spacer/>
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
                                v-if="isEditObjectModalLoader"
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
                    Ви успішно відредагували {{ user.name + " " + user.surname }}
                </span>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="green-darken-1"
                    text
                    @click="closeEditObjectConfirmModal(); $emit('getUsers'); removeAttribute()"
                >
                    Закрити
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!--End Edit User------------------------------------------------------>
</template>

<script setup>
import {ref, onMounted, defineProps, defineEmits} from "vue";
import {useAuthStore} from "@/stores/auth";
import {removeAttribute} from "@/mixins/removeAttribute";
import {editObject} from "@/mixins/editObject";
import {getUserRoles} from "@/mixins/getUserRoles";

onMounted(() => {
    getUserRoles();
    user.value = props.object
});

const props = defineProps({
    'object': Object,
})

defineEmits(['getUsers'])

const store = useAuthStore();

const user = ref({})

const {
    isEditObjectModal,
    isEditObjectConfirmModal,
    isEditObjectModalLoader,
    validationErrorsFromBase,
    editObj,
    closeEditObjectConfirmModal
} = editObject();

const {allUserRoles} = getUserRoles();

const valid = ref(true);
const form = ref(null);

function handleSubmit(user) {
    console.log(form.value)
    form.value.validate();
    if (!valid.value) {
        return;
    }
    prepareUser(user);
}

function prepareUser(user) {
    let params = {
        name: user.name,
        surname: user.surname,
        phone: user.phone,
        email: user.email,
        roles: user.roles,
        is_activated: user.is_activated,
    };
    console.log(params)

    let url = `/api/admin/edit-user/${user.id}`

    editObj(url, params);
}


</script>

<style scoped>

</style>
