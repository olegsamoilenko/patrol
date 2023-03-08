<template>
    <!--Delete Incident-------------------------------------------------->
    <v-btn
        v-if="authStore.user.roles.find((r) => r.name === 'Супер Адміністратор') ||
              authStore.user.roles.find((r) => r.name === 'Аналітик') ||
              authStore.checkPermission('Подія на головній видалити')"
        icon
        color="pink darken-4"
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
                >Бажаєте видалити подію?</span>
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
                    @click="deleteObj(`/api/delete-main-page-incident/${incident.id}`)"
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
                >Ви успішно видалили подію</span>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="green-darken-1"
                    text
                    @click="closeDeleteObjectConfirmModal(); $emit('getIncidents'); removeAttribute()"
                >
                    Закрити
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!--End Delete Incident----------------------------------------------->
</template>

<script setup>
import {defineProps, defineEmits} from "vue";
import {useAuthStore} from "@/stores/auth";
import { removeAttribute } from "@/mixins/removeAttribute";
import { deleteObject } from "@/mixins/deleteObject";

const authStore = useAuthStore();

defineProps({
    'incident' : Object,
})

defineEmits(['getIncidents'])

const {isDeleteObjectModal, isDeleteObjectConfirmationModal, isDeleteObjectModalLoader, deleteObj, closeDeleteObjectConfirmModal} = deleteObject();


</script>

<style scoped>

</style>
