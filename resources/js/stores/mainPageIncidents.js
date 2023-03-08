import {defineStore} from 'pinia'
import {ref} from 'vue'

export const useMainPageIncidentsStore = defineStore(
    'mainPageIncidents',
    () => {
        const mainPageIncidents = ref(null);

        const isLoadedMainPageIncidents = ref(false)

        function getMainPageIncidents() {
            axios
                .get(`/api/get-all-main-page-incidents`)
                .then(({data}) => {
                    mainPageIncidents.value = data.incidents;
                })
                .catch((error) => {
                    console.log(`error`, error)
                })
                .finally(() => {
                    isLoadedMainPageIncidents.value = true;
                });
        }

        return {
            getMainPageIncidents,
            mainPageIncidents,
            isLoadedMainPageIncidents,
        }
    })
