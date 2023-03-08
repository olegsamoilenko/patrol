import {ref} from "vue";

export function softDeleteObject() {
    const isSoftDeleteObjectModal = ref(false);
    const isSoftDeleteObjectConfirmationModal = ref(false);
    const isSoftDeleteObjectModalLoader = ref(false);

    function softDeleteObj(url) {
        console.log(111)
        isSoftDeleteObjectModalLoader.value = true;
        axios
            .delete(url)
            .then(({data}) => {
                isSoftDeleteObjectModal.value = false;
                isSoftDeleteObjectModalLoader.value = false;
                isSoftDeleteObjectConfirmationModal.value = true;
            })
            .catch((error) => {
                console.log("error", error);
            })
            .finally(() => {
                isSoftDeleteObjectModalLoader.value = false;
            });
    }

    function closeSoftDeleteObjectConfirmModal() {
        isSoftDeleteObjectConfirmationModal.value = false;
    }

    return { isSoftDeleteObjectModal, isSoftDeleteObjectConfirmationModal, isSoftDeleteObjectModalLoader, softDeleteObj, closeSoftDeleteObjectConfirmModal };
}
