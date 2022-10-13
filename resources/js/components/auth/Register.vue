<template>
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12 col-md-6 offset-md-3">
                <div class="card shadow sm">
                    <div class="card-body">
                        <h1 class="text-center">Register</h1>
                        <hr />
                        <form
                            class="row"
                            method="post"
                            @submit.prevent="register"
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
                                <label for="name" class="font-weight-bold"
                                    >Name</label
                                >
                                <input
                                    id="name"
                                    v-model="user.name"
                                    type="text"
                                    name="name"
                                    placeholder="Enter name"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group col-12 my-2">
                                <label for="email" class="font-weight-bold"
                                    >Email</label
                                >
                                <input
                                    id="email"
                                    v-model="user.email"
                                    type="text"
                                    name="email"
                                    placeholder="Enter Email"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group col-12">
                                <label for="password" class="font-weight-bold"
                                    >Password</label
                                >
                                <input
                                    id="password"
                                    v-model="user.password"
                                    type="password"
                                    name="password"
                                    placeholder="Enter Password"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group col-12 my-2">
                                <label
                                    for="password_confirmation"
                                    class="font-weight-bold"
                                    >Confirm Password</label
                                >
                                <input
                                    id="password_confirmation"
                                    v-model="user.password_confirmation"
                                    type="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Enter Password"
                                    class="form-control"
                                />
                            </div>
                            <div class="col-12 mb-2">
                                <button
                                    type="submit"
                                    :disabled="processing"
                                    class="btn btn-primary btn-block"
                                >
                                    {{
                                        processing ? "Please wait" : "Register"
                                    }}
                                </button>
                            </div>
                            <div class="col-12 text-center">
                                <label
                                    >Already have an account?
                                    <router-link :to="{ name: 'login' }"
                                        >Login Now!</router-link
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

const user = ref({ name: "", email: "", password: "", password_confirmation:"" });
const validationErrors = ref({});
const processing = ref(false);

const store = useAuthStore();

async function register() {
            processing.value = true
            await axios.get('/sanctum/csrf-cookie')
            await axios.post('/register',user.value).then(response=>{
                validationErrors.value = {}
                store.signIn()
            }).catch(({response})=>{
                console.log(response)
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
