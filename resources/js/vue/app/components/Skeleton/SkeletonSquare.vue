<template>
  <div class="skeleton skeleton-square">
    <div class="skeleton-square__content bg-skeleton-color skeleton-has-loading-animation"></div>
  </div>
</template>

<style scoped>
.skeleton-square {
  position: relative;
  display: block;
  width: 100%;
  height: 100%;
}

.skeleton-square::before {
  content: " ";
  display: block;
  width: 100%;
  padding-top: 100%;
}

.skeleton-square__content {
  top: 0;
  left: 0;
  position: absolute;
  display: block;
  width: 100%;
  height: 100%;
}
</style>
