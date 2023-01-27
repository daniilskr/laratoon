<template>
  <svg
    class="svg-icon"
    :width="width"
    :height="height"
    viewBox="0 0 21 34"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
  >
    <path d="M10 21L0 33.5V7.5V0L10 1L21 0V33.5L10 21Z" :fill="fillColor" />
  </svg>
</template>

<script setup lang="ts">
defineProps(["width", "height", "fillColor"]);
</script>
