<template>
  <div class="skeleton skeleton-line-with-square">
    <div
      class="skeleton-line-with-square__image bg-skeleton-color skeleton-has-loading-animation"
      :style="`width: ${squareWidth}px;height: ${squareWidth}px`"
    ></div>
    <div class="skeleton-line-with-square__info"></div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  squareWidth: number;
}>();
</script>

<style scoped>
.skeleton-line-with-square {
  display: flex;
  margin-bottom: 1px;
  opacity: 1;
}

.skeleton-line-with-square__image {
  width: 80px;
  height: 80px;
}

.skeleton-line-with-square__info {
  flex-grow: 1;
  display: flex;
  justify-content: space-between;
  align-items: stretch;
  transition: 0.32s;
}
</style>
