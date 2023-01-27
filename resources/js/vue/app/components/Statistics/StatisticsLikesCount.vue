<template>
  <div class="statistics-likes-count">
    <div class="statistics-likes-count__heart-icon-wrapper">
      <IconHeart :width="15" :height="15" :stroke-color="'#565555'" :fill-color="'none'" />
    </div>
    <div class="statistics-likes-count__number text-color-accent-5">
      {{ likes }}
    </div>
  </div>
</template>

<script setup lang="ts">
import IconHeart from "@/components/Icons/IconHeart.vue";
defineProps<{
  likes: number;
}>();
</script>

<style scoped>
.statistics-likes-count {
  margin: 0;
  display: flex;
  align-items: center;
}

.statistics-likes-count__heart-icon-wrapper {
  padding-right: 5px;
  padding-bottom: 3px;
}

.statistics-likes-count__number {
  line-height: 10px;
  font-size: 14px;
  font-weight: 500;
}
</style>
