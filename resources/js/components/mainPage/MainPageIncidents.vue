<template>
    <v-card>
        <v-card-title>Відслідковувати</v-card-title>
        <v-card-text>
            <!--Card Incidents------------------------------------------------------------------>
                <v-card
                    v-for="(incident, i) in mainPageIncidentsStore.mainPageIncidents"
                    :key="i"
                    elevation="1"
                    class="mb-3"
                >
                    <v-col>
                        <v-row no-gutters v-if="incident.name || incident.document">

                            <v-col
                                v-if="incident.name"
                                cols="12"
                                sm="6"
                                md="4"
                                class="mb-3 d-flex align-center"
                            >
                                <v-icon class="mr-3">mdi:mdi-account</v-icon>
                                {{ toUpperCase(incident.name) }}
                            </v-col>
                            <v-col
                                v-if="incident.document"
                                cols="12"
                                sm="6"
                                md="4"
                                class="mb-3  d-flex align-center"
                            >
                                <v-icon class="mr-3">mdi:mdi-text-box</v-icon>
                                <span v-if="incident.document_type !== 'Інше'" class="mr-1">{{
                                        incident.document_type
                                    }} №:  </span>
                                <span v-else class="mr-1">{{
                                        incident.document_type_other[0].toUpperCase() + incident.document_type_other.slice(1)
                                    }} №:   </span>
                                {{ incident.document.toUpperCase() }}
                            </v-col>
                        </v-row>
                        <div v-if="incident.car_number || incident.car_type || incident.car_brand || incident.car_model">
                            <v-row>
                                <v-col cols="12" class="mb-2">
                                    <h3>Автомобіль</h3>
                                </v-col>
                            </v-row>
                            <v-row no-gutters>
                                <v-col
                                    v-if="incident.car_number"
                                    cols="12"
                                    sm="6"
                                    md="3"
                                    lg="2"
                                    class="pa-0 mb-3"
                                >
                                    <v-row>
                                        <v-col cols="4">
                                            Номер:
                                        </v-col>
                                        <v-col cols="8">
                                            {{ incident.car_number.toUpperCase() }}
                                        </v-col>
                                    </v-row>
                                </v-col>
                                <v-col
                                    v-if="incident.car_type"
                                    cols="12"
                                    sm="6"
                                    md="3"
                                    lg="2"
                                    class="pa-0 mb-3"
                                >
                                    <v-row>
                                        <v-col cols="4">
                                            Тип:
                                        </v-col>
                                        <v-col cols="8">
                                            {{ incident.car_type }}
                                        </v-col>
                                    </v-row>

                                </v-col>
                                <v-col
                                    v-if="incident.car_brand"
                                    cols="12"
                                    sm="6"
                                    md="3"
                                    lg="2"
                                    class="pa-0 mb-3"
                                >
                                    <v-row>
                                        <v-col cols="4">
                                            Марка:
                                        </v-col>
                                        <v-col cols="8">
                                            {{ incident.car_brand }}
                                        </v-col>
                                    </v-row>
                                </v-col>
                                <v-col
                                    v-if="incident.car_model"
                                    cols="12"
                                    sm="6"
                                    md="3"
                                    lg="3"
                                    class="pa-0 mb-5"
                                >
                                    <v-row>
                                        <v-col cols="4">
                                            Модель:
                                        </v-col>
                                        <v-col cols="8">
                                            {{ toUpperCase(incident.car_model) }}
                                        </v-col>
                                    </v-row>

                                </v-col>
                                <v-col
                                    v-if="incident.car_color"
                                    cols="12"
                                    sm="6"
                                    md="3"
                                    lg="3"
                                    class="pa-0 mb-5"

                                >
                                    <v-row>
                                        <v-col cols="4">
                                            Колір:
                                        </v-col>
                                        <v-col cols="8" :style="{ backgroundColor: incident.car_color }">

                                        </v-col>
                                    </v-row>
                                </v-col>

                            </v-row>
                        </div>
                        <v-row>
                            <v-col cols="12" class="mb-2">
                                <h3>Коментар</h3>
                            </v-col>
                        </v-row>
                        <v-row no-gutters>
                            <v-col
                                cols="12"
                                class="mb-3"

                            >
                                {{ incident.comment }}
                            </v-col>
                        </v-row>
                        <div v-if="incident.media.length" class="mb-3">
                            <v-row>
                                <v-col cols="12" class="mb-2">
                                    <h3>Фото</h3>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col v-for="image in incident.media" :key="image" cols="12">
                                    <v-img
                                        class="bg-white rounded border"
                                        :aspect-ratio="1"
                                        :src="'storage/' + image.id + '/' + image.file_name"
                                    ></v-img>
                                </v-col>
                            </v-row>
                        </div>
                        <v-row no-gutters class="mb-3">
                            <v-col cols="12">
                                <h4>Дата додавання:</h4>
                            </v-col>
                            <v-col cols="8">
                                {{ formattedDate(incident.created_at) }}
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col
                                cols="12"
                                sm="6"
                                md="2"
                                class="d-sm-flex justify-sm-end"
                            >
                                <EditMainPageIncidents
                                    :object="incident"
                                    @get-incidents="mainPageIncidentsStore.getMainPageIncidents"
                                />
                                <DeleteMainPageIncidents
                                    :incident="incident"
                                    @get-incidents="mainPageIncidentsStore.getMainPageIncidents"
                                />

                            </v-col>
                        </v-row>
                    </v-col>
                </v-card>
        </v-card-text>
    </v-card>
</template>

<script setup>
import {useMainPageIncidentsStore} from "@/stores/mainPageIncidents";
import DeleteMainPageIncidents from "@/components/mainPage/DeleteMainPageIncidents.vue";
import EditMainPageIncidents from "@/components/mainPage/EditMainPageIncidents.vue";

import {date} from "@/mixins/date.js"
import {upperCase} from "@/mixins/upperCase";

const mainPageIncidentsStore = useMainPageIncidentsStore();
const {formattedDate} = date()
const {toUpperCase} = upperCase()

</script>

<style scoped>

</style>
