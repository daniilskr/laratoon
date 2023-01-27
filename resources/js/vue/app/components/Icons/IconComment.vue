<template>
  <svg
    class="svg-icon"
    :width="width"
    :height="height"
    viewBox="0 0 113 101"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
  >
    <path d="M107.5 4.5H4V75.5H25.4994L22 94L64 75.5H107.5V4.5Z" :stroke="strokeColor" stroke-width="7" />
  </svg>
</template>

<script setup lang="ts">
defineProps(["width", "height", "strokeColor"]);
</script>
