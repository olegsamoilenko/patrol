<template>
    <!--Filter Feedbacks------------------------------------------------------------>
    <v-card-text>
        <v-row>
            <v-col cols="12" sm="6">
                <v-select
                    v-model="feedbacksStore.filterFeedbacksParams.status"
                    :items="feedbackStatusMap"
                    placeholder="Статус"
                >
                </v-select>
            </v-col>
            <v-col cols="12" sm="6" md="4">
                <v-select
                    v-model="sortFeedbacks"
                    :items="sortFeedbacksMap"
                    placeholder="Дата повідомлення"
                >
                </v-select>
            </v-col>
        </v-row>
    </v-card-text>
    <!--End Filter Feedbacks--------------------------------------------------------->
</template>

<script setup>
import { ref, watch } from "vue";
import {feedbackStatusMap} from "@/utils/maps/feedbackStatusMap";
import { useFeedbacksStore } from "@/stores/feedbacks";

const feedbacksStore = useFeedbacksStore();

// TODO Сделать изначальная загрузка/сортировка
const sortFeedbacks = ref(localStorage.getItem("sortFeedbacksFilter") || "dateDesc");

const sortFeedbacksMap = ref([
    { value: "dateDesc", title: "Від найновіших" },
    { value: "dateAsc", title: "Від найстаріших" },
]);

watch(sortFeedbacks, () => {
    if (sortFeedbacks.value === "dateAsc") {
        feedbacksStore.filterFeedbacksParams.sortDirection = "ASC";
    } else if (sortFeedbacks.value === "dateDesc") {
        feedbacksStore.filterFeedbacksParams.sortDirection = "DESC";
    }
    localStorage.setItem("sortFeedbacksFilter", sortFeedbacks.value)
    feedbacksStore.paginationCurrentPage = 1;
    feedbacksStore.getFeedbacksPagination();
});

watch(feedbacksStore.filterFeedbacksParams, () => {
    localStorage.setItem("sortFeedbacksFilter", sortFeedbacks.value)
    feedbacksStore.paginationCurrentPage = 1;
    feedbacksStore.getFeedbacksPagination();
});


</script>

<style scoped>

</style>
