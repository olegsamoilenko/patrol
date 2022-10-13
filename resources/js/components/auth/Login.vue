<template>
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12 col-md-6 offset-md-3">
                <div class="card shadow sm">
                    <div class="card-body">
                        <h1 class="text-center">Login</h1>
                        <hr />
                        <form
                            class="row"
                            method="post"
                        >
                            <div
                                v-if="Object.keys(validationErrors).length > 0"
                                class="col-12"
                            >
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <li
                                            v-for="(
                                                value, key
                                            ) in validationErrors"
                                            :key="key"
                                        >
                                            {{ value[0] }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <label for="email" class="font-weight-bold"
                                    >Email</label
                                >
                                <input
                                    id="email"
                                    v-model="auth.email"
                                    type="text"
                                    name="email"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group col-12 my-2">
                                <label for="password" class="font-weight-bold"
                                    >Password</label
                                >
                                <input
                                    id="password"
                                    v-model="auth.password"
                                    type="password"
                                    name="password"
                                    class="form-control"
                                />
                            </div>
                            <div class="col-12 mb-2">
                                <button
                                    type="submit"
                                    :disabled="processing"
                                    class="btn btn-primary btn-block"
                                    @click.prevent="login"
                                >
                                    {{ processing ? "Please wait" : "Login" }}
                                </button>
                            </div>
                            <div class="col-12 text-center">
                                <label
                                    >Don't have an account?
                                    <router-link :to="{ name: 'register' }"
                                        >Register Now!</router-link
                                    ></label
                                >
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useAuthStore } from "@/stores/auth";
import { ref } from "vue";

const auth = ref({ email: "", password: "" });
const validationErrors = ref({});
const processing = ref(false);

const store = useAuthStore();

async function login()
{
    console.log(auth.value);
    processing.value = true
    // eslint-disable-next-line no-undef
    await axios.get('/sanctum/csrf-cookie')
    // eslint-disable-next-line no-undef
    await axios.post('/login',auth.value).then((data)=>{
        validationErrors.value = {}
        store.signIn()
    }).catch(({response})=>{
        if(response.status===422){
            validationErrors.value = response.data.errors
        }else{
            validationErrors.value = {}
            alert(response.data.message)
        }
    }).finally(()=>{
        processing.value = false
    })
}
</script>

<style scoped></style>
