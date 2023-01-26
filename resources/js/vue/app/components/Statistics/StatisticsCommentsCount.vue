<template>
  <div class="statistics-comments-count">
    <div class="statistics-comments-count__comment-icon-wrapper">
      <IconComment :width="15" :height="15" :stroke-color="'#565555'" :fill-color="'none'" />
    </div>
    <div class="statistics-comments-count__number text-color-accent-5">
      {{ comments }}
    </div>
  </div>
</template>

<script setup lang="ts">
import IconComment from "@/components/Icons/IconComment.vue";
defineProps<{
  comments: number;
}>();
</script>

<style scoped>
.statistics-comments-count {
  margin: 0;
  display: flex;
  align-items: center;
}

.statistics-comments-count__comment-icon-wrapper {
  padding-right: 5px;
  padding-bottom: 3px;
}

.statistics-comments-count__number {
  line-height: 10px;
  font-size: 14px;
  font-weight: 500;
}
</style>
