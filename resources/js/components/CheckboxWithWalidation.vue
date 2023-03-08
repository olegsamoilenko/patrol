<template>
    <v-checkbox
        v-model="value"
        :label="label"
        :error-messages="errors"
        @blur="handleBlur"
    ></v-checkbox>
</template>

<script setup>
import { defineProps, toRef } from 'vue';
import { useField } from 'vee-validate';

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
});

const { value, handleBlur, errors } = useField(toRef(props, 'name'), undefined);
</script>

<style scoped>

</style>
