<template>
    <v-layout column>
        <v-container fluid>
<h1>{{store.user.name}}</h1>
<!--<div>Сталося пригод/Внесено пригод за останню добу</div>-->
<!--<div>Статистика пригод за тиждень</div>-->
<!--<div>Статистика пригод за місяць</div>-->

        </v-container>
    </v-layout>
</template>

<script setup>
import { useAuthStore } from "@/stores/auth";
import { onMounted, computed, defineProps } from 'vue'
import { useDisplay, useRtl, useLayout, useLocale, useTheme} from 'vuetify'

const store = useAuthStore();

</script>

<style scoped>

</style>
