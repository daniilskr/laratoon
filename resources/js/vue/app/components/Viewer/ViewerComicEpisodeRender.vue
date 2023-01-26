<template>
  <div class="viewer-comic-episode-render">
    <div v-for="page in pages" :key="page.id" class="viewer-comic-episode-render__page">
      <img :src="page.image.medium" class="viewer-comic-episode-render__page__img" />
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  pages: {
    id: number;
    order: number;
    image: {
      medium: string;
    };
  }[];
}>();
</script>

<style scoped>
.viewer-comic-episode-render {
  background: white;
}

.viewer-comic-episode-render__page__img {
  max-width: 100%;
  display: block;
}
</style>
