<template>
  <div class="skeleton skeleton-line">
    <div
      class="skeleton-line__line bg-skeleton-color skeleton-has-loading-animation"
      :style="`height: ${height}px`"
    ></div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  height: number;
}>();
</script>

<style scoped>
.skeleton-line {
  position: relative;
  width: 100%;
  overflow: hidden;
}
.skeleton-line__line {
  position: relative;
  width: 100%;
  overflow: hidden;
}
</style>
