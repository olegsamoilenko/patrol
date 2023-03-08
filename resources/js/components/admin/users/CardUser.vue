<template>
    <v-card-text>
        <!--Card Users------------------------------------------------------------------>
        <v-expansion-panels>
            <v-expansion-panel
                v-for="(user, i) in usersStore.paginationUsers"
                :key="i"
                elevation="1"
            >
                <v-expansion-panel-title>
                    <v-row no-gutters>
                        <v-col
                            cols="12"
                            sm="6"
                            class="align-self-center mb-3"
                        >
                            {{ user.name + " " + user.surname }}
                        </v-col>
                        <v-spacer></v-spacer>
                        <v-col
                            cols="12"
                            sm="6"
                            class="text-start text-sm-end"
                        >
                            <v-chip
                                v-for="role in user.roles"
                                :key="role.id"
                                :class="{'bg-warning': user.is_activated === 'Ні',
                                        'bg-secondary': role.name === 'Адміністратор',
                                }"
                                class="mr-2"
                            >
                                {{ role.name }}
                            </v-chip>
                        </v-col>
                    </v-row>
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                    <v-row no-gutters>
                        <v-col
                            cols="12"
                            sm="6"
                            md="2"
                            class="mb-3"
                            v-if="user.battalion || user.company || user.platoon"
                        >
                            <span v-if="user.battalion">
                                {{ user.battalion }} батальон
                            </span>
                            <span v-if="user.company">
                                {{ user.company }} рота
                            </span>
                            <span v-if="user.platoon">
                                {{ user.platoon }} взвод
                            </span>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="6"
                            md="2"
                            class="mb-3"
                        >
                            <v-icon>mdi:mdi-phone</v-icon>
                            {{ user.phone }}
                        </v-col>
                        <v-col
                            cols="12"
                            sm="6"
                            md="4"
                            class="mb-3 text-sm-end"
                        >
                            <v-icon>mdi:mdi-email</v-icon>
                            {{ user.email }}
                        </v-col>
                        <v-col
                            cols="12"
                            sm="6"
                            md="4"
                            class="mb-3 d-md-flex justify-md-end"
                            v-if="(user.is_activated === 'Ні' && authStore.user.roles.find((r) => r.name === 'Супер Адміністратор')) ||
                                  (user.is_activated === 'Ні' && authStore.checkPermission('Користувач активувати'))"
                        >
                            <ActivateUser
                                :user="user"
                                @get-users="usersStore.getUsers"
                            />
                        </v-col>
                        <!-- TODO Подкорректировать верстку -->
                        <v-col
                            cols="12"
                            sm="6"
                            md="4"
                            class="mb-3 d-md-flex justify-md-end"
                            v-else
                        >
                            <!-- TODO Переделать повторение кода-->

                            <v-row
                                v-if="authStore.user.roles.find((r) => r.name === 'Супер Адміністратор') && user.is_activated === 'Так'">
                                <v-col cols="12">Активован:</v-col>
                            </v-row>

                            <v-row
                                v-if="authStore.user.roles.find((r) => r.name === 'Супер Адміністратор') && user.is_activated === 'Так'">
                                <v-col v-if="user.activated_by" cols="12">
                                    <v-icon>mdi:mdi-account</v-icon>
                                    {{ user.activated_by.user_name + " " + user.activated_by.user_surname }}
                                </v-col>
                            </v-row>
                            <v-row
                                v-if="authStore.user.roles.find((r) => r.name === 'Супер Адміністратор') && user.is_activated === 'Так'">
                                <v-col v-if="user.activated_by" cols="12">
                                    <v-icon>mdi:mdi-phone</v-icon>
                                    {{ user.activated_by.user_phone }}
                                </v-col>
                            </v-row>

                            <v-row
                                v-if="authStore.user.roles.find((r) => r.name === 'Супер Адміністратор') && user.deleted_by !== null">
                                <v-col cols="12">Видаленний:</v-col>
                            </v-row>

                            <v-row
                                v-if="authStore.user.roles.find((r) =>  r.name === 'Супер Адміністратор') && user.deleted_by !== null">
                                <v-col v-if="user.deleted_by" cols="12">
                                    <v-icon>mdi:mdi-account</v-icon>
                                    {{ user.deleted_by.user_name + " " + user.deleted_by.user_surname }}
                                </v-col>
                            </v-row>
                            <v-row
                                v-if="authStore.user.roles.find((r) => r.name === 'Супер Адміністратор') && user.deleted_by !== null">
                                <v-col v-if="user.deleted_by" cols="12">
                                    <v-icon>mdi:mdi-phone</v-icon>
                                    {{ user.deleted_by.user_phone }}
                                </v-col>
                            </v-row>
                        </v-col>

                        <v-col
                            cols="12"
                            sm="6"
                            md="2"
                            class="mb-3 d-sm-flex justify-sm-end"
                        >
                            <EditUser
                                :object="user"
                                @get-users="usersStore.getUsers"
                            />
                            <SoftDeleteUser
                                :user="user"
                                @get-users="usersStore.getUsers"
                            />
                            <DeleteUser
                                :user="user"
                                @get-users="usersStore.getUsers"
                            />

                        </v-col>
                    </v-row>
                </v-expansion-panel-text>
            </v-expansion-panel>
        </v-expansion-panels>
    </v-card-text>
</template>

<script setup>
import {useAuthStore} from "@/stores/auth";
import {useUsersStore} from "@/stores/users";
import DeleteUser from "@/components/admin/users/DeleteUser.vue";
import EditUser from "@/components/admin/users/EditUser.vue";
import ActivateUser from "@/components/admin/users/ActivateUser.vue";
import SoftDeleteUser from "@/components/admin/users/SoftDeleteUser.vue";

const authStore = useAuthStore();
const usersStore = useUsersStore();
</script>

<style scoped>

</style>
