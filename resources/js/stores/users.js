import { defineStore } from 'pinia'
import { ref, reactive } from 'vue'

export const useUsersStore = defineStore(
    'users',
    () => {
        const filterUsersParams = reactive({
            role: localStorage.getItem("usersRoleFilter") ? localStorage.getItem("usersRoleFilter") : '',
            search: null,
            sortBy: "created_at",
            sortDirection: "ASC",
            onlyTrashed: false,
        });

        const paginationUsers = ref(null);
        const paginationCurrentPage = ref(null);
        const paginationLastPage = ref(null);
        const isLoadedUsers = ref(false)

        function getUsers() {
            axios
                .get(`/api/admin/get-users?page=` + paginationCurrentPage.value, {params: filterUsersParams})
                .then(({data}) => {
                    paginationUsers.value = data.users.data;
                    paginationCurrentPage.value = data.users.current_page;
                    paginationLastPage.value = data.users.last_page;
                })
                .catch((error) => {
                    console.log("error", error);
                })
                .finally(() => {
                    isLoadedUsers.value = true;
                });
        }


    return { filterUsersParams, paginationUsers, paginationCurrentPage, paginationLastPage, isLoadedUsers, getUsers }
})
