<template>
  <div :class="['collapsable-block-with-title', { 'collapsable-block-with-title--collapsed': isCollapsed }]">
    <div class="collapsable-block-with-title__control" @click="flip">
      <div class="collapsable-block-with-title__control__title small-bold-title">{{ title }}</div>
      <div class="collapsable-block-with-title__control__icon">
        <IconDropdownArrow :direction="iconDirection" :width="13" :height="12" :stroke-color="'#A5A0A0'" />
      </div>
    </div>
    <div class="collapsable-block-with-title__content padding-t-5">
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import IconDropdownArrow from "@/components/Icons/IconDropdownArrow.vue";

const props = defineProps({ title: String, isInitiallyCollapsed: Boolean });
const isCollapsed = ref(props.isInitiallyCollapsed);

const flip = () => {
  isCollapsed.value = !isCollapsed.value;
};

const iconDirection = computed(() => (isCollapsed.value ? "down" : "up"));
</script>

<style scoped>
.collapsable-block-with-title {
  text-align: left;
  width: 100%;
  display: flex;
  flex-direction: column;
  user-select: none;
}

.collapsable-block-with-title__control {
  cursor: pointer;
  user-select: none;
  display: flex;
  justify-content: space-between;
  transition: 0.12s;
}

.collapsable-block-with-title__control:hover .collapsable-block-with-title__control__title {
  color: #b1b1b1;
}

.collapsable-block-with-title__control__icon {
  display: flex;
  align-items: center;
  padding-right: 12px;
  padding-bottom: 3px;
}

.collapsable-block-with-title__content {
  overflow: hidden;
  transition: max-height 0.6s ease-in, opacity 0.3s ease-in;
  max-height: 100vh;
  opacity: 1;
}

.collapsable-block-with-title--collapsed .collapsable-block-with-title__content {
  opacity: 1;
  max-height: 0;
  opacity: 0;
  transition: none;
  padding-top: 0px;
}
</style>
