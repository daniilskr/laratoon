<template>
  <svg
    :class="['svg-icon', 'icon-triangle-rotate-' + direction]"
    :width="width"
    :height="height"
    viewBox="0 0 22 13"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
  >
    <path d="M10.8085 1L2 12H20L10.8085 1Z" :fill="fillColor" :stroke="fillColor" />
  </svg>
</template>

<script setup lang="ts">
defineProps(["width", "height", "direction", "fillColor"]);
</script>

<style scoped>
.icon-triangle-rotate-down {
  transform: rotateX(180deg);
}

.icon-triangle-rotate-right {
  transform: rotateZ(90deg);
}

.icon-triangle-rotate-left {
  transform: rotateZ(-90deg);
}
</style>
