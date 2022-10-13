import { defineStore } from "pinia";
import { ref } from "vue";
import { useRouter } from "vue-router";

export const useAuthStore = defineStore(
    "auth",
    () => {
        const router = useRouter();
        const authenticated = ref(false);
        const user = ref({});
        function signIn() {
            return axios
                .get("/api/user")
                .then(({ data }) => {
                    user.value = data;
                    authenticated.value = true;
                    router.push({ name: "home" });
                })
                .catch(({ response: err }) => {
                    console.log(err);
                    user.value = {};
                    authenticated.value = false;
                });
        }
        function signOut() {
            user.value = {};
            authenticated.value = false;
        }
        return { authenticated, user, signIn, signOut };
    },
    {
        persist: true,
    }
);
