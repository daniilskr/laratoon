<template>
  <svg
    :class="['svg-icon', 'icon-dropdown-arrow-rotate-' + direction]"
    :width="width"
    :height="height"
    viewBox="0 0 16 9"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
  >
    <path d="M15 0.999998L8 7L1 1" stroke-width="2" stroke-linecap="round" :stroke="strokeColor" />
  </svg>
</template>

<script setup lang="ts">
defineProps(["width", "height", "direction", "strokeColor"]);
</script>

<style scoped>
.icon-dropdown-arrow-rotate-up {
  transform: rotateX(180deg);
}

.icon-dropdown-arrow-rotate-right {
  transform: rotateZ(-90deg);
}

.icon-dropdown-arrow-rotate-left {
  transform: rotateZ(90deg);
}
</style>
