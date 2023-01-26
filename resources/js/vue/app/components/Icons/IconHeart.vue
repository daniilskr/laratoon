<template>
  <svg
    class="svg-icon"
    :width="width"
    :height="height"
    viewBox="0 0 16 14"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
  >
    <path
      d="M1.09937 5.0098C1.67408 7.29272 7.9959 13 7.9959 13C7.9959 13 14.4126 6.73587 14.8924 5.0098C15.3723 3.28374 14.2228 1.06271 11.924 1.00201C10.3102 0.959389 9.05012 1.58543 7.9959 3.86355C7.32599 2.71355 6.08222 0.986635 4.45275 1.00134C2.54841 1.01852 0.52466 2.72689 1.09937 5.0098Z"
      :fill="fillColor"
      :stroke="strokeColor"
    />
  </svg>
</template>

<script setup lang="ts">
defineProps(["width", "height", "fillColor", "strokeColor"]);
</script>
