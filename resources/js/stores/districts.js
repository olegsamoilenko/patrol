import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useDistrictsStore = defineStore('districts', () => {
    const allDistricts = ref(null);
    const isLoadedDistricts = ref(false)
    const allDistrictsSelect = ref([]);

    function getDistricts() {
        axios
            .get("/api/admin/get-all-districts")
            .then(({data}) => {
                allDistricts.value = data.districts;
                allDistrictsSelect.value = [
                    { id: '', title: 'Всі райони'},
                    ...data.districts,
                ];
            })
            .catch((error) => {
                console.log('error', error);
            })
            .finally(() => {
                isLoadedDistricts.value = true;
            });
    }


    return { allDistricts, allDistrictsSelect, isLoadedDistricts, getDistricts }
})
