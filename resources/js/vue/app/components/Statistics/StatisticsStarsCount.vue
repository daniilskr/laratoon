<template>
  <div class="statistics-stars-count">
    <div class="statistics-stars-count__heart-icon-wrapper">
      <IconHeart :width="15" :height="15" :stroke-color="'#565555'" :fill-color="'none'" />
    </div>
    <div class="statistics-stars-count__number text-color-accent-5">
      {{ stars }}
    </div>
  </div>
</template>

<script setup lang="ts">
import IconHeart from "@/components/Icons/IconStar.vue";
defineProps<{
  stars: number;
}>();
</script>

<style scoped>
.statistics-stars-count {
  margin: 0;
  display: flex;
  align-items: center;
}

.statistics-stars-count__heart-icon-wrapper {
  padding-right: 5px;
  padding-bottom: 3px;
}

.statistics-stars-count__number {
  line-height: 10px;
  font-size: 14px;
  font-weight: 500;
}
</style>
