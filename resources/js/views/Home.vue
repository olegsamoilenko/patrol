<template>
    <v-layout column>
        <v-container fluid>

            <div>
                <v-banner>
                    ...
                </v-banner>

                <v-banner mobile-breakpoint="580">
                    ...
                </v-banner>
            </div>

            <v-btn>Ntcn</v-btn>

            <div>
                <v-text-field
                    v-model="content"
                    label="Content"
                ></v-text-field>
                <v-sheet v-mutate="onMutate" v-ripple="{ class: `primary--text` }">
                    {{ content }}
                </v-sheet>
                Total mutations: {{ mutations }}
            </div>



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
import { onMounted, computed } from 'vue'
import { useDisplay, useRtl, useLayout, useLocale, useTheme} from 'vuetify'



export default {

    setup () {


    },

    data: () => ({
        content: 'Foo',
        mutations: 0,
    }),


    methods: {
        onMutate () {
            this.mutations++
        },
    },
    mounted () {
        this.content = 'Bar'


        setTimeout(() => {
            this.content = 'Foobar'

        }, 200)
    },

}


</script>

<style scoped>

</style>
