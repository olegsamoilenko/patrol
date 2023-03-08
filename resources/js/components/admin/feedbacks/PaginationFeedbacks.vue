<template>
    <v-card-text>
        <v-col cols="12">
            <v-pagination
                v-model="feedbacksStore.paginationCurrentPage"
                :length="feedbacksStore.paginationLastPage"
                :total-visible="5"
                @update:modelValue="onPageChange"
            ></v-pagination>
        </v-col>
    </v-card-text>
</template>

<script setup>
import {useFeedbacksStore} from "@/stores/feedbacks";

const feedbacksStore = useFeedbacksStore();

function onPageChange() {
    feedbacksStore.getFeedbacksPagination();
}
</script>

<style scoped>

</style>
