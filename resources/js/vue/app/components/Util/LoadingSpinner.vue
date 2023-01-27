<template>
  <div v-if="show" class="util-loading-spinner"></div>
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";

const props = defineProps<{
  delayMs?: number;
}>();

const show = ref(false);

onMounted(() =>
  setTimeout(() => {
    show.value = true;
  }, props.delayMs ?? 0)
);
</script>

<style>
.util-loading-spinner {
  display: inline-block;
  width: 2rem;
  height: 2rem;
  vertical-align: text-bottom;
  border: 0.25em solid #d7d7d7;
  border-right-color: transparent;
  border-radius: 50%;
  animation: util-loading-spinner-spin 0.75s linear infinite;
}

@keyframes util-loading-spinner-spin {
  100% {
    transform: rotate(360deg);
  }
}
</style>
