<template>
  <div class="statistics-views-count">
    <div class="statistics-views-count__eye-icon-wrapper">
      <IconEye :width="15" :height="15" fill-color="#565555" />
    </div>
    <div class="statistics-views-count__number text-color-accent-5">
      {{ views }}
    </div>
  </div>
</template>

<script setup lang="ts">
import IconEye from "@/components/Icons/IconEye.vue";
defineProps<{
  views: number;
}>();
</script>

<style scoped>
.statistics-views-count {
  margin: 0;
  display: flex;
  align-items: center;
}

.statistics-views-count__eye-icon-wrapper {
  padding-right: 5px;
  padding-bottom: 3px;
}

.statistics-views-count__number {
  line-height: 10px;
  font-size: 14px;
  font-weight: 500;
}
</style>
