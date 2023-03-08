<template>
    <!--Soft Delete User-------------------------------------------------->
    <v-btn
        v-if="store.user.roles.find((r) => r.name === 'Супер Адміністратор') ||
              store.checkPermission('Користувач видалити')"
        icon
        color="red"
        class="mr-5"
        width="var(--v-button-width)"
        height="var(--v-button-height)"
        @click="isSoftDeleteObjectModal = true"
    >
        <v-icon>mdi:mdi-delete</v-icon>
    </v-btn>

    <v-dialog
        v-model="isSoftDeleteObjectModal"
        max-width="600px"
    >
        <v-card>
            <v-card-text>
                <span class="text-h5">
                    Бажаєте видалити {{ user.name + " " + user.surname }}?
                </span>
            </v-card-text>
            <v-card-text>
                <v-col
                    cols="12"
                    class="d-flex justify-center"
                >
                    <v-progress-circular
                        v-if="isSoftDeleteObjectModalLoader"
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
                        isSoftDeleteObjectModal = false;
                        isSoftDeleteObjectModalLoader = false;
                    "
                >
                    Відмінити
                </v-btn>
                <v-btn
                    color="green-darken-1"
                    text
                    :disabled="isSoftDeleteObjectModalLoader"
                    @click="softDeleteObj(`/api/admin/soft-delete-user/${user.id}`)"
                >
                    Видалити
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog
        v-model="isSoftDeleteObjectConfirmationModal"
        max-width="600px"
    >
        <v-card>
            <v-card-text>
                <span class="text-h5">
                    Ви успішно видалили {{ user.name + " " + user.surname }}</span
                >
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="green-darken-1"
                    text
                    @click="closeSoftDeleteObjectConfirmModal(); $emit('getUsers'); removeAttribute()"
                >
                    Закрити
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!--End Soft Delete User----------------------------------------------->
</template>

<script setup>
import {defineProps, defineEmits} from "vue";
import {useAuthStore} from "@/stores/auth";
import { removeAttribute } from "@/mixins/removeAttribute";
import { softDeleteObject } from "@/mixins/softDeleteObject";

const store = useAuthStore();

defineProps({
    'user' : Object,
})

defineEmits(['getUsers'])

const { isSoftDeleteObjectModal, isSoftDeleteObjectConfirmationModal, isSoftDeleteObjectModalLoader, softDeleteObj, closeSoftDeleteObjectConfirmModal } = softDeleteObject();
</script>

<style scoped>

</style>
