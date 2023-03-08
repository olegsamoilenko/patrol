import {ref} from "vue";

export function deleteObject() {
    const isDeleteObjectModal = ref(false);
    const isDeleteObjectConfirmationModal = ref(false);
    const isDeleteObjectModalLoader = ref(false);

    function deleteObj (url) {
        isDeleteObjectModalLoader.value = true;
        axios
            .delete(url)
            .then(({data}) => {
                isDeleteObjectModal.value = false;
                isDeleteObjectModalLoader.value = false;
                isDeleteObjectConfirmationModal.value = true;
            })
            .catch((error) => {
                console.log("error", error);
            })
            .finally(() => {
                isDeleteObjectModalLoader.value = false;
            });
    }

    function closeDeleteObjectConfirmModal() {
        isDeleteObjectConfirmationModal.value = false;
    }

    return { isDeleteObjectModal, isDeleteObjectConfirmationModal, isDeleteObjectModalLoader, deleteObj, closeDeleteObjectConfirmModal };
}
