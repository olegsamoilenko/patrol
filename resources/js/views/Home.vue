<template>
    <v-layout column>
        <v-container fluid>

            <div>
                <v-toolbar
                    dark
                    prominent
                    src="../../images/arm-wrestling-567950__340.jpg"
                >
                    <v-app-bar-nav-icon></v-app-bar-nav-icon>

                    <v-toolbar-title>Vuetify</v-toolbar-title>

                    <v-spacer></v-spacer>

                    <v-btn icon>
                        <v-icon>mdi-export</v-icon>
                    </v-btn>
                </v-toolbar>
            </div>

            <img src="../../images/arm-wrestling-567950__340.jpg" alt="">

<!--            <v-card class="">-->
<!--                <v-card-title>Home</v-card-title>-->
<!--                <p class="">You name is  <b>{{store.user.name}}</b></p>-->
<!--                <p class="">You email is  <b>{{store.user.email}}</b></p>-->
<!--                <p v-if="!store.isEmptyUser" class="">You role is  <b v-for="role in store.user.roles" :key="role.id">{{role.role}}, </b></p>-->
<!--            </v-card>-->




        </v-container>
    </v-layout>
</template>

<script>
// import { useAuthStore } from "@/stores/auth";
//
// const store = useAuthStore();


export default {
    data () {

    },

}


</script>

<style scoped>

</style>
