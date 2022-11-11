<template>
    <v-layout column>
        <v-container fluid>
            <v-card>
                <v-card-title> Події</v-card-title>
<!--                &lt;!&ndash;Filter Incidents&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&ndash;&gt;-->
<!--                <v-card-text>-->
<!--                    <v-row>-->
<!--                        <v-col cols="12" sm="6">-->
<!--                            <v-select-->
<!--                                v-model="selectedUsersRole"-->
<!--                                :items="allUsersRolesMap"-->
<!--                                :item-title="(item) => item.name"-->
<!--                                :item-value="(item) => item.slug"-->
<!--                                placeholder="Всі ролі"-->
<!--                            >-->
<!--                            </v-select>-->
<!--                        </v-col>-->
<!--                        <v-col cols="12" sm="6">-->
<!--                            <v-select-->
<!--                                v-model="sortUsers"-->
<!--                                :items="sortUsersMap"-->
<!--                                placeholder="Дата реєстрації"-->
<!--                            >-->
<!--                            </v-select>-->
<!--                        </v-col>-->
<!--                        <v-col cols="12" class="relative">-->
<!--                            <v-text-field-->
<!--                                v-model="searchUsersInput"-->
<!--                                variant="solo"-->
<!--                                label="Пошук Користувача"-->
<!--                                append-inner-icon="mdi:mdi-magnify"-->
<!--                                single-line-->
<!--                                hide-details-->
<!--                            ></v-text-field>-->
<!--                        </v-col>-->
<!--                    </v-row>-->
<!--                </v-card-text>-->
<!--                &lt;!&ndash;End Filter Incidents-&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&#45;&ndash;&gt;-->

                <v-card-text v-if="isLoader">
                    <v-col cols="12" class="d-flex justify-center">
                        <v-progress-circular
                            :size="50"
                            indeterminate
                            color="green"
                        ></v-progress-circular>
                    </v-col>
                </v-card-text>
                <div v-else>
                    <v-card-text>
                        <!--Card Incidents------------------------------------------------------------------>
                        <v-expansion-panels>
                            <v-expansion-panel
                                v-for="(incident, i) in paginationIncidents"
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
                                            Патруль №: {{ incident.patrol }} | {{ humanizeDate(incident.created_at) }}
                                        </v-col>
                                        <v-spacer></v-spacer>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            class="text-start text-sm-end"
                                        >
                                            {{ incident.address }}
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-title>
                                <!--TODO: Адаптивный дизайн немного гуляет-->
                                <v-expansion-panel-text>
                                    <v-row no-gutters v-if="incident.name">

                                            <v-col
                                                cols="12"
                                                sm="6"
                                                md="4"
                                                class="mb-3 d-flex align-center"
                                            >
                                                <v-icon class="mr-3">mdi:mdi-account</v-icon>
                                                {{ toUpperCase(incident.name) }}
                                            </v-col>
                                            <v-col
                                                cols="12"
                                                sm="6"
                                                md="4"
                                                class="mb-3  d-flex align-center"
                                            >
                                                <v-icon class="mr-3">mdi:mdi-text-box</v-icon>
                                                <span v-if="incident.document_type !== 'Інше'" class="mr-1">{{ incident.document_type }} №:  </span>
                                                <span v-else class="mr-1">{{ incident.document_type_other[0].toUpperCase() + incident.document_type_other.slice(1) }} №:   </span>
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
                                                    <v-col  cols="4">
                                                        <h4>Номер:</h4>
                                                    </v-col>
                                                    <v-col  cols="8">
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
                                                    <v-col  cols="4">
                                                        <h4>Тип:</h4>
                                                    </v-col>
                                                    <v-col  cols="8">
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
                                                    <v-col  cols="4">
                                                       <h4> Марка:</h4>
                                                    </v-col>
                                                    <v-col  cols="8">
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
                                                    <v-col  cols="4">
                                                        <h4>Модель:</h4>
                                                    </v-col>
                                                    <v-col  cols="8">
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
                                                    <v-col  cols="4">
                                                        <h4>Колір:</h4>
                                                    </v-col>
                                                    <v-col  cols="8" :style="{ backgroundColor: incident.car_color }">

                                                    </v-col>
                                                </v-row>
                                            </v-col>

                                        </v-row>
                                    </div>
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
                                    <v-row no-gutters>
                                        <v-col  cols="4">
                                            <h4>Дата:</h4>
                                        </v-col>
                                        <v-col  cols="8">
                                            {{ formattedDate(incident.created_at) }}
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-text>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-card-text>
                    <v-card-text>
                        <v-col cols="12">
                            <v-pagination
                                v-model="paginationCurrentPage"
                                :length="paginationLastPage"
                                :total-visible="5"
                                @update:modelValue="onPageChange"
                            ></v-pagination>
                        </v-col>
                    </v-card-text>
                </div>
            </v-card>
        </v-container>
    </v-layout>
</template>

<script setup>
import { onMounted, ref, watch, computed } from 'vue';
import dayjs from 'dayjs/esm/index.js';
import uk from '../locale/dayjs/uk.js';
import relativeTime from 'dayjs/esm/plugin/relativeTime/index.js';
import { brandMap } from "@/utils/maps/BrandMap";

// import moment from "moment/dist/moment"
// import uk from "../locale/moment/uk.js";

onMounted(() => {
    dayjs.extend(relativeTime);
    getIncidents()
});

const isLoader = ref(false);

// Load Incidents ========================================
const paginationIncidents = ref(null);
const paginationCurrentPage = ref(null);
const paginationLastPage = ref(null);
async function getIncidents() {
    isLoader.value = true;
    await axios
        .get(`/get-all-incidents?page=` + paginationCurrentPage.value)
        .then(({data}) => {
            paginationIncidents.value = data.incidents.data;
            paginationCurrentPage.value = data.incidents.current_page;
            paginationLastPage.value = data.incidents.last_page;
            console.log("data", data.incidents);

        })
        .catch(({ response }) => {
            console.log(`error`, response)
        })
        .finally(() => {
            isLoader.value = false;
        });
}

function humanizeDate(date) {
    if (!date){
        return null;
    }
    return dayjs(date).locale('uk', uk).fromNow();
    // return moment(date).locale('uk', uk).fromNow();
}

function formattedDate(date) {
    if (!date){
        return null;
    }
    return dayjs(date).locale('uk', uk).format('DD MMMM YYYY, HH:mm');
    // return moment(date).locale('uk', uk).fromNow();
}

function toUpperCase(name) {
    return name
        .toLowerCase()
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
}

// Pagination ======================================
function onPageChange() {
    getIncidents();
}
</script>

<style scoped>

</style>
