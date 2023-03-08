import { ref } from "vue";
export function getUserRoles() {
    const allUserRoles = ref(null);

        axios
            .get("/api/admin/get-all-roles")
            .then(({data}) => {
                allUserRoles.value = data.roles;
            })
            .catch((error) => {
                console.log("error", error);
            })

    return {allUserRoles};
}
