import { defineStore } from 'pinia'
import { ref, reactive } from 'vue'

export const useFeedbacksStore = defineStore('feedbacks', () => {
    const paginationFeedbacks = ref(null);
    const paginationCurrentPage = ref(null);
    const paginationLastPage = ref(null);
    const isLoadedFeedbacks = ref(false);

    const filterFeedbacksParams = reactive({
        status: null,
        sortBy: "created_at",
        sortDirection: "ASC",
    });

    function getFeedbacksPagination() {
        axios
            .get(`/api/admin/get-feedbacks-pagination?page=` + paginationCurrentPage.value, {params: filterFeedbacksParams})
            .then(({data}) => {
                paginationFeedbacks.value = data.feedbacks.data;
                paginationCurrentPage.value = data.feedbacks.current_page;
                paginationLastPage.value = data.feedbacks.last_page;
            })
            .catch((error) => {
                console.log('error', error);
            })
            .finally(() => {
                isLoadedFeedbacks.value = true;
            });
    }




    return { isLoadedFeedbacks, paginationFeedbacks, paginationCurrentPage, paginationLastPage, filterFeedbacksParams, getFeedbacksPagination }
})
