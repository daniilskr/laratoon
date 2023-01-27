<template>
  <div class="util-viewport-intersection-observer" ref="observedElement"></div>
</template>

<script setup lang="ts">
import { onMounted, onUnmounted, ref, watch } from "vue";

const observedElement = ref<null | Element>(null);
const observer = ref<null | IntersectionObserver>(null);

const emit = defineEmits<{
  (e: "intersects"): void;
}>();

const props = defineProps<{
  keepObserverAlive?: boolean; // Observer stops working after first intersection
  // has occured, provide 'keep-observer-alive' prop to keep emitting events
  restoreOnChangeOf?: string | number | boolean; // If observer is not kept alive, but parent component is re-used, you may want to
  // watch some parent property to restore observer whenever the component is re-used
  // Though putting :key and v-if on this component is a way better approach
}>();

const observerCallback = (entries: IntersectionObserverEntry[]) => {
  if (entries.some((e) => e.isIntersecting)) {
    if (!props.keepObserverAlive) stopObserve();
    emit("intersects");
  }
};

const startObserve = () => {
  if (observedElement.value) {
    observer.value = new IntersectionObserver(observerCallback);
    observer.value.observe(observedElement.value);
  }
};

const stopObserve = () => {
  if (observer.value) {
    observer.value.disconnect();
    observer.value = null;
  }
};

onMounted(() => startObserve());
onUnmounted(() => stopObserve());

watch(
  () => props.restoreOnChangeOf,
  () => {
    if (!observer.value) {
      startObserve();
    }
  }
);
</script>

<style>
.util-viewport-intersection-observer {
  width: 100%;
  height: 0;
}
</style>
