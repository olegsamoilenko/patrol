<template>
    <v-text-field
        v-model="value"
        :prepend-inner-icon="icon"
        :error-messages="errors"
        :label="label"
        :type="type"
        density="comfortable"
        @blur="handleBlur"
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
    icon: {
        type: String,
    },
});

const { value, handleBlur, errors } = useField(toRef(props, 'name'), undefined);
</script>

<style>
.v-messages{
    flex: 0 1 auto;
}
</style>
