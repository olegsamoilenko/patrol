import { defineStore } from "pinia";
import { ref, computed } from "vue";

export const useAuthStore = defineStore(
    "auth",
    () => {
        const authenticated = ref(false);
        const user = ref({});

        const isEmptyUser = computed(() => Object.keys(user.value).length === 0)

        function signIn() {
            return axios
                .get("/api/user")
                .then(({ data }) => {
                    console.log(data);
                    user.value = data
                    authenticated.value = true;
                    this.router.push({name: "home"})
                })
                .catch((err) => {
                    console.log(err);
                    user.value = {};
                    authenticated.value = false;
                    this.router.push({name: "login"})
                });
        }
        function signOut() {
            user.value = {};
            authenticated.value = false;
            this.router.push({name: "login"})
        }

        function checkPermission(permission) {
            return user.value.roles.find(r => r.permissions.find(p => p.name === permission))
        }

        return { authenticated, user, signIn, signOut, isEmptyUser, checkPermission };
    },
    {
        persist: true,
    }
);
