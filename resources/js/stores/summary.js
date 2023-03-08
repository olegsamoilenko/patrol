import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useSummaryStore = defineStore('summary', () => {
    let summary = ref(null)
    const isLoadedSummary = ref(false)

    async function getSummary() {
        await axios
            .get("/api/get-summary")
            .then(({data}) => {
                summary.value = data.summary;
            })
            .catch((error) => {
                console.log('error', error);
            })
            .finally(() => {
                isLoadedSummary.value = true
            });
    }

    return { getSummary, summary, isLoadedSummary}
})
