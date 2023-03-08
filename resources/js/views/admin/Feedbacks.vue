<template>
    <v-layout column>
        <v-container fluid>
            <v-card>
                <v-card-title>Повідомлення</v-card-title>
                <div v-if="isLoader">
                    <Loader />
                </div>
                <div v-else>
                    <FilterFeedbacks />
                    <CardFeedbacks />
                    <PaginationFeedbacks />
                </div>
            </v-card>
        </v-container>
    </v-layout>
</template>

<script setup>
import {onMounted, computed,} from "vue";
import { useUsersStore } from "@/stores/users";
import {useFeedbacksStore} from "@/stores/feedbacks";
import Loader from "@/components/Loader.vue";
import FilterFeedbacks from "@/components/admin/feedbacks/FilterFeedbacks.vue";
import CardFeedbacks from "@/components/admin/feedbacks/CardFeedbacks.vue";
import PaginationFeedbacks from "@/components/admin/feedbacks/PaginationFeedbacks.vue";

const usersStore = useUsersStore();
const feedbacksStore = useFeedbacksStore();


onMounted(() => {
    usersStore.getUsers()
    feedbacksStore.getFeedbacksPagination();
});


const isLoader = computed(() => {
    return !usersStore.isLoadedUsers
});

</script>

<style>
.v-messages {
    flex: 0 1 auto;
}
</style>

