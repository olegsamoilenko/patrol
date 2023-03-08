<template>
    <!--Delete District-->
    <v-btn
        icon
        color="red"
        width="var(--v-button-width)"
        height="var(--v-button-height)"
        @click="isDeleteObjectModal = true"
    >
        <v-icon>mdi:mdi-delete</v-icon>
    </v-btn>

    <v-dialog
        v-model="isDeleteObjectModal"
        max-width="600px"
    >
        <v-card>
            <v-card-text>
                <span class="text-h5"
                >Бажаєте видалити
                    {{
                        district.title
                    }}?</span
                >
            </v-card-text>
            <v-card-text>
                <v-col
                    cols="12"
                    class="d-flex justify-center"
                >
                    <v-progress-circular
                        v-if="isDeleteObjectModalLoader"
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
                        isDeleteObjectModal = false;
                        isDeleteObjectModalLoader = false;
                    "
                >
                    Відмінити
                </v-btn>
                <v-btn
                    color="green-darken-1"
                    text
                    :disabled="isDeleteObjectModalLoader"
                    @click="deleteObj(district, 'district')"
                >
                    Видалити
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog
        v-model="isDeleteObjectConfirmationModal"
        max-width="600px"
    >
        <v-card>
            <v-card-text>
                <span class="text-h5"
                >Ви успішно видалили
                    {{
                        district.title
                    }}</span
                >
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="green-darken-1"
                    text
                    @click="closeDeleteObjectConfirmModal; $emit('getDistricts'); removeAttribute()"
                >
                    Закрити
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!--End Delete District-->
</template>

<script setup>
import { removeAttribute } from "@/mixins/removeAttribute";
import { deleteObject } from "@/mixins/deleteObject";

defineProps({
    'district' : Object,
})

defineEmits(['getDistricts'])

const {isDeleteObjectModal, isDeleteObjectConfirmationModal, isDeleteObjectModalLoader, deleteObj, closeDeleteObjectConfirmModal} = deleteObject();
</script>

<style scoped>

</style>
