<template>
  <svg
    class="svg-icon"
    :width="width"
    :height="height"
    viewBox="0 0 12 10"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
  >
    <path d="M2 3.39269C3.92 8.49709 2.8 11.3683 10 1" :stroke-width="strokeWidth" :stroke="strokeColor" />
  </svg>
</template>

<script setup lang="ts">
defineProps(["width", "height", "strokeWidth", "strokeColor"]);
</script>
