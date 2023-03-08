<template>
    <v-card-text>
        <!--Card Incidents------------------------------------------------------------------>
        <v-expansion-panels>
            <v-expansion-panel
                v-for="(feedback, i) in feedbacksStore.paginationFeedbacks"
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
                            {{ feedback.topic }}
                        </v-col>
                        <v-spacer></v-spacer>
                        <v-col
                            cols="12"
                            sm="6"
                            class="text-start text-sm-end"
                        >
                            <span :style="{color: statusColor(feedback.status)}">{{ feedback.status }}</span> |
                            {{ humanizeDate(feedback.created_at) }}
                        </v-col>
                    </v-row>
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                    <v-row no-gutters>

                        <v-col
                            cols="12"
                            sm="6"
                            md="4"
                            class="mb-3  d-flex align-center"
                        >
                            <v-icon class="mr-3">mdi:mdi-text-box</v-icon>
                            {{ feedback.text }}
                        </v-col>

                        <v-col
                            cols="12"
                            sm="6"
                            md="4"
                            class="mb-3 d-flex align-center"
                        >
                            <v-icon class="mr-3">mdi:mdi-account</v-icon>
                            {{ toUpperCase(feedback.user.name) }} {{toUpperCase(feedback.user.surname)}}
                        </v-col>
                        <v-col
                            cols="12"
                            sm="6"
                            md="4"
                            class="mb-3  d-flex align-center"
                        >
                            <v-icon class="mr-3">mdi:mdi-phone</v-icon>
                            {{ feedback.user.phone }}
                        </v-col>

                        <v-col
                            cols="12"
                            sm="6"
                            md="2"
                            class="d-sm-flex justify-sm-end"
                        >
                            <EditFeedback
                                :object="feedback"
                                @get-feedbacks="feedbacksStore.getFeedbacksPagination"
                            />
                            <DeleteFeedback
                                :feedback="feedback"
                                @get-feedbacks="feedbacksStore.getFeedbacksPagination"
                            />

                        </v-col>
                    </v-row>
                </v-expansion-panel-text>
            </v-expansion-panel>
        </v-expansion-panels>
    </v-card-text>
</template>

<script setup>
import {useFeedbacksStore} from "@/stores/feedbacks";
import EditFeedback from "@/components/admin/feedbacks/EditFeedback.vue";
import DeleteFeedback from "@/components/admin/feedbacks/DeleteFeedback.vue";
import {date} from "@/mixins/date.js"
import {upperCase} from "@/mixins/upperCase";

const feedbacksStore = useFeedbacksStore();

const {humanizeDate} = date()
const {toUpperCase} = upperCase()

// const feedback

function statusColor(status) {
    if (status === 'Новий') {
        return 'blue'
    } else if (status === 'В процесі') {
        return 'orange'
    } else if (status === 'Виконано') {
        return 'green'
    } else if (status === 'Відхилено') {
        return 'red'
    } else {
        return 'black'
    }
}
</script>

<style scoped>

</style>
