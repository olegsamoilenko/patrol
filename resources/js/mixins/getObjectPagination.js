import {ref} from "vue";

export function getObjectPagination() {
    const paginationObject = ref(null);
    const paginationCurrentPage = ref(null);
    const paginationLastPage = ref(null);


    function getObjPagination() {
        isLoader.value = true;
        let params = {
            role: selectedUsersRole.value,
            search: searchUsersInput.value,
            sortBy: sortUsersBy.value,
            sortDirection: sortUsersDirection.value,
            onlyTrashed: onlyTrashed.value,
        };
        axios
            .get(`/api/admin/get-users?page=` + paginationCurrentPage.value, {params})
            .then(({data}) => {
                paginationUsers.value = data.users.data;
                paginationCurrentPage.value = data.users.current_page;
                paginationLastPage.value = data.users.last_page;
            })
            .catch((error) => {
                console.log("error", error);
            })
            .finally(() => {
                isLoader.value = false;
            });
    }

    return { isEditObjectModal, isEditObjectConfirmationModal, isEditObjectModalLoader, validationErrorsFromBase, editObj, closeEditObjectConfirmModal };
}
