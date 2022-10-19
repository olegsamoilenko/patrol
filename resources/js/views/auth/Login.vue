<template>
    <v-layout>
        <v-container fluid>
            <v-card class="mx-auto mt-12" max-width="400px">

                <v-progress-linear
                    v-if="processing"
                    class="position-absolute"
                    style="z-index: 1"
                    color="var(--v-progress-linear-color)"
                    height="2"
                    indeterminate
                ></v-progress-linear>

                <v-card-title>Логін</v-card-title>
                <v-card-text>
                    <Form
                        as="v-form"
                        :validation-schema="schema"
                        @submit="onSubmit"
                    >
                        <TextFieldWithValidation
                            name="email"
                            label="Пошта"
                            type="email"
                            icon="mdi:mdi-email"
                            :validation-errors-from-base="validationErrorsFromBase.email"
                        />

                        <TextFieldWithValidationPassword
                            name="password"
                            label="Пароль"
                            type="password"
                            :validation-errors-from-base="validationErrorsFromBase.password"
                        />

                        <v-spacer></v-spacer>

                        <v-btn
                            :disabled="processing"
                            color="green"
                            type="submit"
                        >
                            Увійти
                        </v-btn>
                    </Form>
                </v-card-text>
            </v-card>
        </v-container>
    </v-layout>
</template>
<script setup>
import { Form } from "vee-validate";
import * as yup from "yup";
import TextFieldWithValidation from "@/components/TextFieldWithValidation.vue";
import TextFieldWithValidationPassword from "@/components/TextFieldWithValidationPassword.vue";

import { useAuthStore } from "@/stores/auth";
import { ref } from "vue";

const validationErrorsFromBase = ref({});
const processing = ref(false);

const store = useAuthStore();

const schema = yup.object({
    email: yup.string().email('Електронна адреса має бути дійсною').required('Введіть адресу Вашої електронної пошти').label("Email"),
    password: yup.string().min(8,'Пароль має бути не менше 8 символів').required('Введіть пароль'),
});

async function onSubmit(values) {
    processing.value = true;
    // eslint-disable-next-line no-undef
    await axios.get("/sanctum/csrf-cookie");
    // eslint-disable-next-line no-undef
    await axios
        .post("/login", values)
        .then((data) => {
            validationErrorsFromBase.value = {};
            store.signIn();
        })
        .catch(({ response }) => {
            if (response.status === 422) {
                validationErrorsFromBase.value = response.data.errors;
                console.log(validationErrorsFromBase.value)
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
<style scoped></style>
