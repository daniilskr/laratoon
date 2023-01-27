<template>
  <svg
    class="svg-icon"
    :width="width"
    :height="height"
    viewBox="0 0 140 146"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
  >
    <path
      d="M1.5 94L139 1.5L116 140L75.5 113.5L55 143.5V113.5L122.5 28L43 100.5L1.5 94Z"
      :fill="fillColor"
      :stroke="strokeColor"
    />
  </svg>
</template>

<script setup lang="ts">
defineProps(["width", "height", "fillColor", "strokeColor"]);
</script>
