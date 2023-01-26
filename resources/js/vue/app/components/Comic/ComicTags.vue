<template>
  <div class="comic-tags">
    <div v-for="tag in tags" :key="tag.id" class="comic-tags__item-wrapper">
      <ComicTagItem :id="tag.id" :name="tag.name" />
    </div>
  </div>
</template>

<script setup lang="ts">
import ComicTagItem from "@/components/Comic/ComicTagItem.vue";
defineProps<{
  tags: {
    id: number;
    name: string;
  }[];
}>();
</script>

<style scoped>
.comic-tags {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  gap: 5px;
}

/* .comic-tags__item-wrapper {
    margin-bottom: 1px; 
  } */
</style>
