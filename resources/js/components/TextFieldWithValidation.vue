<template>
    <v-text-field
        v-model="value"
        :error-messages="errors"
        :label="label"
        :type="type"
        @blur="handleBlur"
    />
    <span class="text-error text-caption" v-if="validationErrorsFromBase">{{ validationErrorsFromBase[0] }}</span>
</template>

<script setup>
import { defineProps, toRef } from 'vue';
import { useField } from 'vee-validate';

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
    },
});

const { value, handleBlur, errors } = useField(toRef(props, 'name'), undefined);
</script>

<style scoped>

</style>
