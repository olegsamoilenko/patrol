<template>
    <v-text-field
        v-model="value"
        :type="visible ? 'text' : 'password'"
        :append-inner-icon="visible ? 'mdi:mdi-eye-off-outline' : 'mdi:mdi-eye-outline'"
        prepend-inner-icon="mdi:mdi-lock"
        :error-messages="errors"
        :label="label"
        @blur="handleBlur"
        counter
        density="comfortable"
        @click:append-inner="visible = !visible"
    >
    <template #details>
        <span class="text-error text-caption" v-if="validationErrorsFromBase">{{ validationErrorsFromBase[0] }}</span>
        <v-spacer />
    </template>
    </v-text-field>
</template>

<script setup>
import { defineProps, toRef } from 'vue';
import { useField } from 'vee-validate';
import { ref } from "vue";

const visible = ref(false);

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    validationErrorsFromBase: {
        type: Object,
        required: false,
        default: null,
    },
});

const { value, handleBlur, errors } = useField(toRef(props, 'name'), undefined);
</script>

<style scoped>
.v-messages{
    flex: 0 1 auto;
}
</style>
