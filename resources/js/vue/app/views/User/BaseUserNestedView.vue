<template>
  <div class="base-user-nested-view">
    <div class="base-user-nested-view__navbar z-index-header-navbar padding-x-25 padding-y-10" ref="refStickyNavbar">
      <slot name="navbar"></slot>
    </div>
    <div class="base-user-nested-view__content-wrapper" ref="refContentWrapper">
      <slot name="content"></slot>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref, nextTick } from "vue";

const refContentWrapper = ref<HTMLElement | null>(null);
const refStickyNavbar = ref<HTMLElement | null>(null);

// <layout stuff>
const calculateNavbarHeightDependants = () => {
  if (refContentWrapper.value && refStickyNavbar.value) {
    refContentWrapper.value.style.paddingTop = `${refStickyNavbar.value.offsetHeight}px`;
    refStickyNavbar.value.style.width = `${refContentWrapper.value.clientWidth}px`;
  }
};

onMounted(() => nextTick(() => calculateNavbarHeightDependants()));
// </layout stuff>
</script>

<style scoped>
.base-user-nested-view {
  background: white;
  position: relative;
}

.base-user-nested-view__navbar {
  position: fixed;
  background: white;
  display: flex;
  border-bottom: 1px solid #f5f5f5;
}
</style>
