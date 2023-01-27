<template>
  <div>
    <router-view v-if="isAuthenticated" />
    <DemoLoginPopup v-if="showLoginPopup" @close-popup="showLoginPopup = false" />
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import DemoLoginPopup from "@/components/Demo/DemoLoginPopup.vue";
import { useAuth } from "./composable/useAuth";
const { isAuthenticated } = useAuth();
const showLoginPopup = ref(!isAuthenticated.value);
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Hind:wght@400;500;600;700&display=swap");
@import "./assets/css/util.css";

/* TODO maybe?
  1) отрефактирить цвета в css-variables
  3) Убрать декоративные отступы в виде margin, заменив их на padding
*/
</style>
