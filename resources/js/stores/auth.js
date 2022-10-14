import { defineStore } from "pinia";
import { ref, computed } from "vue";

export const useAuthStore = defineStore(
    "auth",
    () => {
        // const router = useRouter();
        const authenticated = ref(false);
        const user = ref({});

        const isEmptyUser = computed(() => Object.keys(user.value).length === 0)

        function signIn() {
            return axios
                .get("/api/user")
                .then(({ data }) => {
                    user.value = data;
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
            console.log(user)
            user.value = {};
            console.log(user.value)
            authenticated.value = false;
            console.log(authenticated.value)
        }
        return { authenticated, user, signIn, signOut, isEmptyUser };
    },
    {
        persist: true,
    }
);
