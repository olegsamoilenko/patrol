import {defineStore} from "pinia";
import {ref} from "vue";

export const useUserRolesStore = defineStore(
    "userRoles",
    () => {

        const allUserRoles = ref(null);
        const isLoadedUserRoles = ref(false)

        const allUsersRolesSelect = ref([]);

        function getUserRoles() {
            axios
                .get("/api/admin/get-all-roles")
                .then(({data}) => {
                    allUserRoles.value = data.roles;
                    allUsersRolesSelect.value = [
                        { id: '', name: 'Всі ролі'},
                        ...data.roles,
                    ];
                })
                .catch((error) => {
                    console.log("error", error);
                })
                .finally(() => {
                    isLoadedUserRoles.value = true;
                });
        }

        return {getUserRoles, allUserRoles, allUsersRolesSelect, isLoadedUserRoles};

    }
);
