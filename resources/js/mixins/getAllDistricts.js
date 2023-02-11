import { ref, onMounted  } from "vue";
export function getAllDistricts() {
    const allDistricts = ref(null);

    onMounted(() => {
        axios
            .get("/api/admin/get-all-districts")
            .then(({ data }) => {
                allDistricts.value = data.districts;
            })
            .catch((error) => {
                console.log('error', error);
            });
    });

    return { allDistricts };
}
