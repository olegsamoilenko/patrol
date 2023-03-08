<template>
    <v-autocomplete
        v-model="value"
        :error-messages="errors"
        :label="label"
        :type="type"
        density="comfortable"
        :items="data"
        item-title="number"
        item-value="number"
        :disabled="disabled"
        @blur="handleBlur"
    >
        <template #details>
            <span class="text-error text-caption" v-if="validationErrorsFromBase">{{ validationErrorsFromBase[0] }}</span>
            <v-spacer />
        </template>
    </v-autocomplete>
</template>

<script setup>
import { defineProps, toRef } from 'vue';
import { useField } from 'vee-validate';

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
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
    disabled: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const { value, handleBlur, errors } = useField(toRef(props, 'name'), undefined);
</script>

<style scoped>

</style>
