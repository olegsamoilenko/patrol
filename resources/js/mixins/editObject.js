import {ref} from "vue";

export function editObject() {
    const isEditObjectModal = ref(false);
    const isEditObjectConfirmModal = ref(false);
    const isEditObjectModalLoader = ref(false);
    const validationErrorsFromBase = ref({});

    function editObj(url, data, config = null) {
        isEditObjectModalLoader.value = true;
        axios
            .post(url, data, config)
            .then(({data}) => {
                isEditObjectModal.value = false;
                isEditObjectModalLoader.value = false;
                isEditObjectConfirmModal.value = true;
            })
            .catch(({response}) => {
                if (response.status === 422) {
                    validationErrorsFromBase.value = response.data.errors;
                } else {
                    validationErrorsFromBase.value = {};
                    alert(response.data.message);
                }
            })
            .finally(() => {
                isEditObjectModalLoader.value = false;
            });
    }

    function closeEditObjectConfirmModal() {
        isEditObjectConfirmModal.value = false;
    }

    return { isEditObjectModal, isEditObjectConfirmModal, isEditObjectModalLoader, validationErrorsFromBase, editObj, closeEditObjectConfirmModal };
}
