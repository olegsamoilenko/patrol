import {defineStore} from 'pinia'
import {ref, reactive} from 'vue'

export const useIncidentsStore = defineStore(
    'incidents',
    () => {
        const filterIncidentsParams = reactive({
            district: null,
            search: null,
            sortBy: "created_at",
            sortDirection: "ASC",
            onlyTrashed: false,
            onlyTheirOwn: false,
        });


        const paginationIncidents = ref(null);
        const paginationCurrentPage = ref(null);
        const paginationLastPage = ref(null);
        const isLoadedIncidents = ref(false)

        async function getIncidents() {
            await axios
                .get(`/api/get-all-incidents?page=` + paginationCurrentPage.value, {params: filterIncidentsParams})
                .then(({data}) => {
                    paginationIncidents.value = data.incidents.data;
                    paginationCurrentPage.value = data.incidents.current_page;
                    paginationLastPage.value = data.incidents.last_page;
                })
                .catch((error) => {
                    console.log(`error`, error)
                })
                .finally(() => {
                    isLoadedIncidents.value = true;
                });
        }

        const incidentCount = ref(0)
        const todayIncidentCount = ref(0)
        const lastWeekIncidentCount = ref(0)
        const lastMonthIncidentCount = ref(0)
        const isLoadedIncidentsStat = ref(false)


        async function getIncidentStat() {
            await axios
                .get("/api/get-incident-statistics")
                .then(({data}) => {
                    incidentCount.value = data.incidentCount;
                    todayIncidentCount.value = data.todayIncidentCount;
                    lastWeekIncidentCount.value = data.lastWeekIncidentCount;
                    lastMonthIncidentCount.value = data.lastMonthIncidentCount;
                })
                .catch((error) => {
                    console.log('error', error);
                })
                .finally(() => {
                    isLoadedIncidentsStat.value = true
                });
        }

        return {
            getIncidents,
            filterIncidentsParams,
            paginationIncidents,
            paginationCurrentPage,
            paginationLastPage,
            isLoadedIncidents,
            getIncidentStat,
            incidentCount,
            todayIncidentCount,
            lastWeekIncidentCount,
            lastMonthIncidentCount,
            isLoadedIncidentsStat
        }
    })
