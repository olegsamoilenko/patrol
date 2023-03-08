<template>
    <v-layout>
        <v-container fluid>
            <v-card class="mx-auto mt-12" max-width="400px" location="">
                <div>
                    <v-progress-linear
                        v-if="processing"
                        class="position-absolute"
                        style="z-index: 1"
                        color="var(--v-progress-linear-color)"
                        height="2"
                        indeterminate
                    ></v-progress-linear>

                    <v-card-title>Реєстрація</v-card-title>
                    <v-card-text>
                        <Form
                            as="v-form"
                            :validation-schema="schema"
                            @submit="onSubmit"
                        >

                            <TextFieldWithValidation
                                name="name"
                                label="Ім'я"
                                type="text"
                                icon="mdi:mdi-account-tie"
                                :validation-errors-from-base="validationErrorsFromBase.name"
                            />

                            <TextFieldWithValidation
                                name="surname"
                                label="Прізвище"
                                type="text"
                                icon="mdi:mdi-account-tie"
                                :validation-errors-from-base="validationErrorsFromBase.surname"
                            />

                            <TextFieldWithValidation
                                name="phone"
                                label="Телефон в форматі 0501234567"
                                type="text"
                                icon="mdi:mdi-cellphone"
                                :validation-errors-from-base="validationErrorsFromBase.phone"
                            />

                            <div class="platoon-inputs">
                                <TextFieldWithValidation
                                    name="battalion"
                                    label="Батальон"
                                    type="number"
                                    class="mr-3"
                                />

                                <TextFieldWithValidation
                                    name="company"
                                    label="Рота"
                                    type="number"
                                    class="mr-3"
                                />

                                <TextFieldWithValidation
                                    name="platoon"
                                    label="Взвод"
                                    type="number"
                                />
                            </div>

                            <TextFieldWithValidation
                                name="email"
                                label="Пошта"
                                type="email"
                                icon="mdi:mdi-email"
                                :validation-errors-from-base="validationErrorsFromBase.email"
                            />

                            <!-- TODO Добавить выбор взвода -->

                            <TextFieldWithValidationPassword
                                name="password"
                                label="Пароль"
                                type="password"
                                :validation-errors-from-base="validationErrorsFromBase.password"
                            />

                            <TextFieldWithValidationPassword
                                name="password_confirmation"
                                label="Підтвердіть Пароль"
                                type="password"
                                :validation-errors-from-base="validationErrorsFromBase.password_confirmation"
                            />

                            <v-spacer></v-spacer>

                            <v-btn
                                color="green"
                                type="submit"
                                :disabled="processing"
                            >
                                Зареєструватися
                            </v-btn>
                        </Form>
                    </v-card-text>
                </div>
            </v-card>
        </v-container>
    </v-layout>
</template>

<script setup>
import { Form } from "vee-validate";
import * as yup from "yup";
import "yup-phone";
import TextFieldWithValidation from "@/components/TextFieldWithValidation.vue";
import TextFieldWithValidationPassword from "@/components/TextFieldWithValidationPassword.vue";
import { useAuthStore } from "@/stores/auth";
import { ref } from "vue";

const validationErrorsFromBase = ref({});
const processing = ref(false);

const authStore = useAuthStore();

const schema = yup.object({
    name: yup.string().required('Введіть своє Ім\'я').label("Name").matches(/^[а-яА-Яа-щА-ЩЬьЮюЯяЇїІіЄєҐґ0-9]+$/,'Введіть Ім\'я українською.'),
    surname: yup.string().required('Введіть своє Прізвище').label("Name").matches(/^[а-яА-Яа-щА-ЩЬьЮюЯяЇїІіЄєҐґ0-9]+$/,'Введіть Прізвище українською.'),
    phone: yup.string().phone("UA", true, 'Введіть телефон в форматі 0501234567').required('Введіть телефон в форматі 0501234567'),
    is_headquarters: yup.boolean(),
    battalion: yup.number(),
    company: yup.number(),
    platoon: yup.number(),
    email: yup.string().email('Електронна адреса має бути дійсною').required('Введіть адресу Вашої електронної пошти').label("Email").when(() => {validationErrorsFromBase.value = {}}),
    password: yup.string('test').min(8,'Пароль має бути не менше 8 символів').required('Введіть пароль'),
    password_confirmation: yup
        .string()
        .oneOf([yup.ref("password")], "Паролі не співпадають")
        .required('Введіть пароль')
        .label("Password confirmation"),
});


async function onSubmit(values) {
    console.log(values);
    processing.value = true;
    await axios.get("/sanctum/csrf-cookie");
    await axios
        .post("/register", values)
        .then((response) => {
            validationErrorsFromBase.value = {};
            authStore.signIn();
        })
        .catch(({ response }) => {
            console.log(response);
            if (response.status === 422) {
                validationErrorsFromBase.value = response.data.errors;
            } else {
                validationErrorsFromBase.value = {};
                alert(response.data.message);
            }
        })
        .finally(() => {
            processing.value = false;
        });
}
</script>

<style scoped>
.platoon-inputs {
    display: flex;
    justify-content: space-between;
}
</style>
