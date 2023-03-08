<template>
    <!-- Activate User-------------------------------------------------------------->
    <v-btn
        color="warning"
        @click="isActivateUserModal = true"
    >Активувати
    </v-btn>

    <v-dialog
        v-model="isActivateUserModal"
        max-width="600px"
    >
        <v-card>
            <v-card-text>
                <span class="text-h5">
                    Бажаєте активувати {{ user.name + " " + user.surname }}?
                </span>
            </v-card-text>
            <v-card-text>
                <v-col
                    cols="12"
                    class="d-flex justify-center"
                >
                    <v-progress-circular
                        v-if="isActivateUserModalLoader"
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
                        isActivateUserModal = false;
                        isActivateUserModalLoader = false;
                    "
                >
                    Відмінити
                </v-btn>
                <v-btn
                    color="green-darken-1"
                    text
                    :disabled="isActivateUserModalLoader"
                    @click="activateUser(user)"
                >
                    Активувати
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog
        v-model="isActivateUserConfirmationModal"
        max-width="600px"
    >
        <v-card>
            <v-card-text>
                <span class="text-h5">Ви успішно активували {{ user.name + " " + user.surname }}</span
                >
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="green-darken-1"
                    text
                    @click="closeActivateUserConfirmModal; $emit('getUsers'); removeAttribute()"
                >
                    Закрити
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!--End Activate User----------------------------------------------------------->
</template>

<script setup>
import { ref } from "vue";
import { removeAttribute } from "@/mixins/removeAttribute";

const isActivateUserModal = ref(false);
const isActivateUserConfirmationModal = ref(false);
const isActivateUserModalLoader = ref(false);

defineProps({
    'user' : Object,
})

defineEmits(['getUsers'])

function activateUser(user) {
    isActivateUserModalLoader.value = true;
    axios
        .post(`/api/admin/activate-user/${user.id}`)
        .then(({data}) => {
            isActivateUserModal.value = false;
            isActivateUserModalLoader.value = false;
            isActivateUserConfirmationModal.value = true;
        })
        .catch((error) => {
            console.log("error", error);
        })
        .finally(() => {
            isActivateUserModalLoader.value = false;
        });
}

function closeActivateUserConfirmModal() {
    isActivateUserConfirmationModal.value = false;
}
</script>

<style scoped>

</style>
