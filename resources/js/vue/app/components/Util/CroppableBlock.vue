<template>
  <div class="croppable-block">
    <div class="croppable-block__content" :style="`margin-top: -${marginTop}px`">
      <div class="croppable-block__content__wrapper" ref="refContentWrapper">
        <slot />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, defineProps } from "vue";
const refContentWrapper = ref<HTMLElement | null>(null);
const props = defineProps<{ crop: number }>();
const marginTop = computed(() => Math.max(Math.min(props.crop, refContentWrapper.value?.clientHeight ?? 0), 0));
</script>

<style>
.croppable-block__content__wrapper {
  overflow: hidden;
}
</style>
